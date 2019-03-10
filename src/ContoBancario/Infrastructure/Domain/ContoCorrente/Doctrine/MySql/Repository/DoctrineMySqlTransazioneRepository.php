<?php

namespace ContoBancario\Infrastructure\Domain\ContoCorrente\Doctrine\MySql\Repository;

use ContoBancario\Domain\ContoCorrente\Aggregate\IdTransazione;
use ContoBancario\Domain\ContoCorrente\Aggregate\Transazione;
use ContoBancario\Domain\ContoCorrente\Repository\TransazioneRepository;
use DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\Repository\DoctrineRepository;

class DoctrineMySqlTransazioneRepository extends DoctrineRepository implements TransazioneRepository
{
   public function salva(Transazione $transazione): void
   {
      $this->em->persist($transazione);
      $this->em->flush();
   }

   public function nextIdentity(): IdTransazione
   {
      return IdTransazione::create();
   }

   protected function getEntityAliasName(): string
   {
      return 't';
   }
}
