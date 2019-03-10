<?php

namespace ContoBancario\Domain\ContoCorrente\Aggregate;

use DateTimeImmutable;

class Transazione
{
   private const VERSA = '+';
   private const PRELEVA = '-';

   private $idTransazione;
   private $somma;
   private $direzione;
   private $idConto;
   private $dataContabile;

   private function __construct(IdTransazione $idTransazione, IdConto $idConto, int $somma, string $direzione)
   {
      $this->idTransazione = $idTransazione;
      $this->idConto = $idConto;
      $this->somma = $somma;
      $this->direzione = $direzione;
      $this->dataContabile = new DateTimeImmutable();
   }

   public static function versa(IdTransazione $idTransazione, IdConto $idConto, int $somma)
   {
      return new self($idTransazione, $idConto, $somma, self::VERSA);
   }

   public static function preleva(IdTransazione $idTransazione, IdConto $idConto, int $somma)
   {
      return new self($idTransazione, $idConto, $somma, self::PRELEVA);
   }
}
