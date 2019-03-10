<?php

namespace ContoBancario\Application\Conto\Service;

class CreaContoRequest
{
   private $titolare;

   public function __construct(string $titolare)
   {
      $this->titolare = $titolare;
   }

   public function getTitolare(): string
   {
      return $this->titolare;
   }
}
