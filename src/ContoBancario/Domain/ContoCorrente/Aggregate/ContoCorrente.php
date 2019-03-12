<?php

namespace ContoBancario\Domain\ContoCorrente\Aggregate;

use DDDStarterPack\Domain\Aggregate\IdentifiableDomainObject;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use LogicException;

class ContoCorrente implements IdentifiableDomainObject
{
   private $idConto;

   /** @var int */
   private $saldo;

   /** @var string */
   private $titolare;

   /** @var int */
   private $fido;

   private $transazioni;

   private function __construct(IdConto $idConto, string $titolare)
   {
      $this->idConto = $idConto;
      $this->titolare = $titolare;

      $this->saldo = 0;
      $this->fido = 0;

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
      $expr = new Comparison('dataContabile', '=', 'jwage');
      $criteria = new Criteria();
      $criteria->where($expr);

      $matched = $this->transazioni->matching($criteria);

      if ($somma > $this->saldo) {
         throw new LogicException('Importo non disponibile');
      }

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
}
