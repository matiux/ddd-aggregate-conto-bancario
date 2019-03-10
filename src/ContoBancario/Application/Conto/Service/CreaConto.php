<?php

namespace ContoBancario\Application\Conto\Service;

use ContoBancario\Domain\ContoCorrente\Aggregate\ContoCorrente;
use ContoBancario\Domain\ContoCorrente\Repository\ContoCorrenteRepository;

class CreaConto
{
   private $contoCorrenteRepository;

   public function __construct(ContoCorrenteRepository $contoCorrenteRepository)
   {
      $this->contoCorrenteRepository = $contoCorrenteRepository;
   }

   public function execute(CreaContoRequest $request): string
   {
      $contoCorrente = ContoCorrente::crea(
         $this->contoCorrenteRepository->nextIdentity(),
         $request->getTitolare()
      );

      $this->contoCorrenteRepository->salva($contoCorrente);

      return (string)$contoCorrente->id();
   }
}
