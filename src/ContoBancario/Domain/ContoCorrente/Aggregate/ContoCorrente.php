<?php

namespace ContoBancario\Domain\ContoCorrente\Aggregate;

use DDDStarterPack\Domain\Aggregate\IdentifiableDomainObject;
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

   private function __construct(IdConto $idConto, string $titolare)
   {
      $this->idConto = $idConto;
      $this->titolare = $titolare;

      $this->saldo = 0;
      $this->fido = 0;
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

   public function preleva(IdTransazione $idTransazione, int $somma): Transazione
   {
      if ($somma > $this->saldo) {
         throw new LogicException('Importo non disponibile');
      }

      $this->saldo -= $somma;

      return Transazione::preleva($idTransazione, $this->idConto, $somma);
   }

   public function versa(IdTransazione $idTransazione, int $somma): Transazione
   {
      $this->saldo += $somma;

      return Transazione::versa($idTransazione, $this->idConto, $somma);
   }
}
