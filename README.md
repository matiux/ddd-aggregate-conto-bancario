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

#Versamento effettuato.
```

Provare anche un versamento in un conto non esistente... Funziona!
```
./sf banca:conto:versa 5cce80be-e606-44f0-8775-51c7e3ca389d 150

#Versamento effettuato.
```

## Step 1
Con l'attuale implementazione, possiamo effettuare tutti i versamenti o prelievi che vogliamo. Tuttavia ci sono vari problemi:
* Possiamo creare movimenti su conti correnti non esistenti
* Il conto corrente non si accorge dei movimenti effettuati
* Possiamo prelevare all'infinito

Questo è un problema, indipendentemente dalla tecnologia utilizzata per la persistenza. Anche con un'implementazione in memoria, 
possiamo effettuare movimenti su conti correnti sbagliatio. Stiamo violando una regola di business.
Ovviamente, questo può essere risolto usando una chiave esterna nel database, ma cosa succede se non abbiamo un database 
con chiavi esterne?
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
