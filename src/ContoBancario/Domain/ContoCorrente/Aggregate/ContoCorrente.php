<?php

namespace ContoBancario\Domain\ContoCorrente\Aggregate;

use ContoBancario\Domain\ContoCorrente\Exception\TransazioneInvalidaException;
use DateTime;
use DDDStarterPack\Domain\Aggregate\IdentifiableDomainObject;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;

class ContoCorrente implements IdentifiableDomainObject
{
   private $idConto;

   /** @var int */
   private $saldo;

   /** @var string */
   private $titolare;

   /** @var int */
   private $maxPrelievi;

   private $transazioni;

   private function __construct(IdConto $idConto, string $titolare)
   {
      $this->idConto = $idConto;
      $this->titolare = $titolare;

      $this->saldo = 0;
      $this->maxPrelievi = 3;

      $this->transazioni = new Transazioni();
   }

   public static function crea(IdConto $idConto, string $titolare): self
   {
      return new self($idConto, $titolare);
   }

   public function saldo(): int
   {
      return $this->saldo;
   }

   public function id(): IdConto
   {
      return $this->idConto;
   }

   public function preleva(IdTransazione $idTransazione, int $somma): void
   {
      $this->validaNumeroPrelievo();
      $this->validaSaldo($somma);

      $this->saldo -= $somma;
      $transazione = Transazione::preleva($idTransazione, $this->idConto, $somma);
      $this->transazioni->add($transazione);
   }

   public function versa(IdTransazione $idTransazione, int $somma): void
   {
      $this->saldo += $somma;

      $transazione = Transazione::versa($idTransazione, $this->idConto, $somma);

      $this->transazioni->add($transazione);
   }

   private function validaNumeroPrelievo(): void
   {
      $expr1 = new Comparison('dataContabile', Comparison::GTE, new DateTime('today'));
      $expr2 = new Comparison('dataContabile', Comparison::LT, new DateTime('tomorrow'));
      $expr3 = new Comparison('direzione', Comparison::EQ, Transazione::PRELEVA);
      $criteria = new Criteria();
      $criteria->where($expr1)->andWhere($expr2)->andWhere($expr3);

      if ($this->maxPrelievi <= $this->transazioni->matching($criteria)->count()) {
         throw new TransazioneInvalidaException('Numero massimo di transazioni giornaliero raggiunto');
      }
   }

   private function validaSaldo(int $somma): void
   {
      if ($somma > $this->saldo) {
         throw new TransazioneInvalidaException('Importo non disponibile');
      }
   }
}
