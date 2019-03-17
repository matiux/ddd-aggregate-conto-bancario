Esempio di aggregato in DDD
========================

Esempio di aggregato con gestione delle invarianti

## Preparazione ambiente sviluppo
L'applicazione funziona all'interno di un conainer docker. Preparare l'ambiente in questo modo:

#### Clone del progetto
```
git clone git@github.com:matiux/ddd-aggregate-conto-bancario && cd ddd-aggregate-conto-bancario
```

#### Variabili d'ambiente
```
cp .env .env.local
```
## Sviluppo
Usare il comando `dc` come shortcut a docker-compose
```
./dc up -d
./dc enter
./setup --force
```
Il container php è configurato per far comunicare Xdebug con PhpStorm

## Accessi

* `localhost:8080` risponde nginx
* `localhost:8081` phpmyadmin
* All'interno del container PHP, il database è raggiungibile con l'host `servicedb` alla porta `3306`
* All'esterno del container, il database è raggiungibile con l'host `127.0.0.1` alla porta `3307`

## Comandi e Aliases all'interno del container PHP

* `sf` è un alias a `bin/console` per usare la console di Symfony
* `sfcc` è un alias a `rm -Rf var/cache/*` per svuotare la cache
* `xon` - Abilita xdebug
* `xoff` - Disabilita xdebug

## Utilizzo

Creazione di un conto corrente
```
./sf banca:conto:crea matiux

#Contro creato: 1cce80be-e606-44f0-8775-51c7e3ca389b
```
Effettuare un versamento
```
./sf banca:conto:versa 1cce80be-e606-44f0-8775-51c7e3ca389b 150

#Versamento effettuato.
```

Effettuare un prelievo
```
./sf banca:conto:preleva 1cce80be-e606-44f0-8775-51c7e3ca389b 150

#Prelievo effettuato.
```

## Step 1
Con l'attuale implementazione, possiamo effettuare tutti i versamenti o prelievi che vogliamo. Tuttavia ci sono vari problemi:
* Possiamo creare movimenti su conti correnti non esistenti
* Il conto corrente non si accorge dei movimenti effettuati
* Possiamo prelevare all'infinito

Questo è un problema, indipendentemente dalla tecnologia utilizzata per la persistenza. Anche con un'implementazione in memoria, 
possiamo effettuare movimenti su conti correnti sbagliati. Stiamo violando una regola di business. Ovviamente, questo può essere 
risolto usando una chiave esterna nel database, ma cosa succede se non abbiamo un database con chiavi esterne?
## Step 2
Ora abbiamo quantomeno gestito la presenza di un conto corrente, ma c'è un problema nell'esecuzione del controllo 
nell'application service (`PrelevaService` e `VersaService`). Se qualche altro client, diverso da questo servizio
(un servizio di dominio o qualsiasi altro client) vuole effettuare un movimento su un conto inesistente può farlo 
in quanto il controllo è a livello di application service.
```php
#Da qualche parte in giro per l'applicazione

$idConto = IdConto::create($request->getIdConto());

$transazione = Transazione::preleva(
  $this->transazioneRepository->nextIdentity(),
  $idConto,
  $request->getSomma()
);
```
## Step 3
In questo step abbiamo risolto vari problemi identificati nello step_1 e nello step_2. Innanzi tutto avendo
portato i metodi `versa()` e `preleva()` dentro all'aggregato `ContoCorrente`, non abbiamo più alcun modo 
di creare transazioni per conti inesistenti in quanto ora, grazie ai due metodi factory, è l'aggregato 
che gestisce i versamenti e i prelievi. Questa è l'implementazione del principio `Tell Don't Ask` che come dice 
Martin Fowler ci ricorda che l'orientamento all'oggetto riguarda il raggruppamento dei dati con le 
funzioni che operano su quei dati. Ci ricorda che piuttosto che chiedere a un oggetto i dati e agire 
su quei dati, dovremmo invece dire a un oggetto cosa fare. Questo incoraggia a spostare il comportamento 
all'interno degli oggetti di dominio (aggregati). L'implementazione di questo principio ci ha dato anche modo 
di incapsulare la logica di prelievo e versamento gestendo il saldo del conto corrente all'interno 
dell'oggetto `ContoCorrente` notificando eventuali anomalie (Per ora tramite delle eccezioni di dominio).
Abbiamo risolto tutti i problemi legati alle violazioni di dominio evidenziati negli step precedenti ma abbiamo
reso più visibile un altro problema. Ora a ogni prelievo e a ogni versamento dobbiamo salvare sia lo stato
della nuova transazione che lo stato del conto corrente. Queste due entità sono strettamente legate e tutte 
le invarianti di dominio ruotanno attorno alla consistenza di questi due oggetti; salvarli in due momenti 
dististi potrebbe generare un saldo che non tiene traccia di una transazione, ad esempio. Il salvataggio di 
queste due entità dovrebbe essere atomico, o tutto o niente. Probabilmente a qualcuno verrà in mente di 
racchiudere il tutto dentro a una transazione. Potrebbe essere uan soluzione, ma riguarda l'infrastruttura e 
non il dominio. Cosa succederebbe se usassimo un sistema di persistenza che per qualche motivo non supporta 
le transazioni in stile MySql? Il fatto che gli oggetti `ContoCorrente` e `Transazione` siano strettagmente 
legati tra di loro è un vincolo di dominio; il conto corrente e il suo saldo dipende dalle transizioni. 
Vediamo quindi come gestire questa cosa a livello di dominio piuttosto che a livello infrastrutturale introducendo 
anche una nuova regola di business: non si possono fare più di 3 prelievi al giorno. 

## Step 4
In questo step abbiamo raggiunto la completa e corretta implementazione dell'aggregato. `ContoCorrente` protegge
le nostre invarianti di dominio e funge da `Aggregate Root` per le inviarianti e le transazioni. Per ogni operazione
compiuta sul conto, è il savataggio dello stesso che rende effettivi tutti i cambi di stato al suo interno:
```php
   $this->contoCorrenteRepository->salva($contoCorrente);
   
   //$this->transazioneRepository->salva($transazione); #Non serve più
```
A questo punto possiamo notare altre 2 cose:
* Ubiquitous language: è il linguaggio comune e rigoroso tra gli sviluppatori, gli utenti e gli esperti di dominio. Questo
si porta dietro anche uno dei dubbi che spesso gli sviluppatori hanno; codice italiano o codice inglese? Per quanto agli 
sviluppatori possa piacere lo scrivere tutto in inglese, il codice dovrebbe rispettare il più possibile il linguaggio
di dominio. Questo comporta molti vantaggi:
    * Il codice è auto esplicativo e quindi la documentazione è il codice stesso.
    * Quando parliamo con i colleghi di specifiche e concetti, questi hanno un rapporto 1:1 con il codice
    * Un nuovo membro del team trarrà vataggio dal leggere il codice e trovarsi i concetti scritti come gli sonop stati spiegati
* L'organizzazione dei file e delle cartelle si basa sull'architettura esagonale (https://matiux.github.io/slides/hexagonal-architecture/)
che ci suggerisce di separare la nostra applicazioni in 3 diversi layers annidati: Dominio, Applicazione e  Infrastrtuttura e ogni
layer contiene le informazioni su come parlare con i layer sottostanti (le porte) e le concretizzazioni di queste informazioni
(gli adapters)

