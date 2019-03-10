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
#### Gestione container
Usare il comando dc come shortcut a docker-compose
```
./dc up -d
./dc enter - Entra come utente nel container php
./dc down -v
```
## Sviluppo

#### Entrare nel container PHP per lo sviluppo
```
./dc enter
composer install
```
Il container php è configurato per far comunicare Xdebug con PhpStorm

## Accessi

* `localhost:8080` risponde nginx
* `localhost:8081` phpmyadmin
* All'interno del container PHP, il database è raggiungibile con l'host `servicedb` alla porta `3306`
* All'esterno del container, il database è raggiungibile con l'host `127.0.0.1` alla porta `3307`

## Comandi e Aliases all'interno del container PHP

* `test` è un alias a `./vendor/bin/simple-phpunit`
* `sf` è un alias a `bin/console` per usare la console di Symfony
* `sfcc` è un alias a `rm -Rf var/cache/*` per svuotare la cache
* `memflush` è un alias a `echo \"flush_all\" | nc servicememcached 11211 -q 1"` per svuotare memcached
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

##Tag v1.0.0
Con l'attuale implementazione, possiamo effettuare tutti i versamenti o prelievi che vogliamo. Tuttavia ci sono ben 2 problemi:
* Possiamo creare movimenti su conti correnti non esistenti
* Il conto corrente non si accorge dei movimenti effettuati
* Possiamo prelevare all'infinito

Questo è un problema, indipendentemente dalla tecnologia utilizzata per la persistenza. Anche con un'implementazione in memoria, 
possiamo effettuare movimenti su conti correnti sbagliatio. Stiamo violando una regola di business.
Ovviamente, questo può essere risolto usando una chiave esterna nel database, ma cosa succede se non stiamo usando un database 
con chiavi esterne?
