<?php

namespace ContoBancario\Domain\ContoCorrente\Aggregate;

use DDDStarterPack\Domain\Aggregate\IdentifiableDomainObject;

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
}
