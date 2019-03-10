<?php

namespace ContoBancario\Domain\ContoCorrente\Repository;

use ContoBancario\Domain\ContoCorrente\Aggregate\ContoCorrente;
use ContoBancario\Domain\ContoCorrente\Aggregate\IdConto;

interface ContoCorrenteRepository
{
   public function salva(ContoCorrente $conto): void;

   public function nextIdentity(): IdConto;

   public function idConto(IdConto $idConto): ?ContoCorrente;
}
