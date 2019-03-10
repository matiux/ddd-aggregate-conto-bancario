<?php

namespace ContoBancario\Infrastructure\Domain\ContoCorrente\Doctrine\MySql\Aggregate;

use DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\DoctrineEntityId;

class DoctrineIdTransazione extends DoctrineEntityId
{
   public function getName()
   {
      return 'IdTransazione';
   }

   protected function getNamespace(): string
   {
      return 'ContoBancario\Domain\ContoCorrente\Aggregate';
   }
}
