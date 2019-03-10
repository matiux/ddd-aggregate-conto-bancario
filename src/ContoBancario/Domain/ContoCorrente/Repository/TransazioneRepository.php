<?php

namespace ContoBancario\Domain\ContoCorrente\Repository;

use ContoBancario\Domain\ContoCorrente\Aggregate\IdTransazione;
use ContoBancario\Domain\ContoCorrente\Aggregate\Transazione;

interface TransazioneRepository
{
   public function salva(Transazione $transazione): void;

   public function nextIdentity(): IdTransazione;
}
