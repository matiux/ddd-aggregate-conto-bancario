<?php

namespace ContoBancario\Infrastructure\Domain\ContoCorrente\Doctrine\MySql\Aggregate;

use DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\DoctrineEntityId;

class DoctrineIdConto extends DoctrineEntityId
{
   public function getName()
   {
      return 'IdConto';
   }

   protected function getNamespace(): string
   {
      return 'ContoBancario\Domain\ContoCorrente\Aggregate';
   }
}
