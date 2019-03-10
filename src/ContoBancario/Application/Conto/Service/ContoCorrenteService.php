<?php

namespace ContoBancario\Application\Conto\Service;

use ContoBancario\Domain\ContoCorrente\Aggregate\ContoCorrente;
use ContoBancario\Domain\ContoCorrente\Aggregate\IdConto;
use ContoBancario\Domain\ContoCorrente\Repository\ContoCorrenteRepository;
use ContoBancario\Domain\ContoCorrente\Repository\TransazioneRepository;
use LogicException;

abstract class ContoCorrenteService
{
   protected $transazioneRepository;
   protected $contoCorrenteRepository;

   public function __construct(TransazioneRepository $transazioneRepository, ContoCorrenteRepository $contoCorrenteRepository)
   {
      $this->transazioneRepository = $transazioneRepository;
      $this->contoCorrenteRepository = $contoCorrenteRepository;
   }

   protected function findContoCorrenteOrFail(IdConto $idConto): ContoCorrente
   {
      if (!$contoCorrente = $this->contoCorrenteRepository->idConto($idConto)) {
         throw new LogicException('Conto corrente non esistente');
      }

      return $contoCorrente;
   }
}
