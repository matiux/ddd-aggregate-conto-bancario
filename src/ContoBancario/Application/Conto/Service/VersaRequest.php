<?php

namespace ContoBancario\Application\Conto\Service;

class VersaRequest
{
   private $somma;
   private $idConto;

   public function __construct(int $somma, string $idConto)
   {
      $this->somma = $somma;
      $this->idConto = $idConto;
   }

   public function getSomma(): int
   {
      return $this->somma;
   }

   public function getIdConto(): string
   {
      return $this->idConto;
   }
}
