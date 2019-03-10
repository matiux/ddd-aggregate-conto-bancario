<?php

namespace ContoBancario\Infrastructure\Domain\ContoCorrente\Doctrine\MySql\Repository;

use ContoBancario\Domain\ContoCorrente\Aggregate\ContoCorrente;
use ContoBancario\Domain\ContoCorrente\Aggregate\IdConto;
use ContoBancario\Domain\ContoCorrente\Repository\ContoCorrenteRepository;
use DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\Repository\DoctrineRepository;

class DoctrineMySqlContoCorrenteRepository extends DoctrineRepository implements ContoCorrenteRepository
{
   protected function getEntityAliasName(): string
   {
      return 'cc';
   }

   public function salva(ContoCorrente $conto): void
   {
      $this->em->persist($conto);
      $this->em->flush();
   }

   public function nextIdentity(): IdConto
   {
      return IdConto::create();
   }
}
