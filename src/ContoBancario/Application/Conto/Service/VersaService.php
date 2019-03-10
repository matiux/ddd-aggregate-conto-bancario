<?php

namespace ContoBancario\Application\Conto\Service;

use ContoBancario\Domain\ContoCorrente\Aggregate\IdConto;
use ContoBancario\Domain\ContoCorrente\Aggregate\Transazione;
use ContoBancario\Domain\ContoCorrente\Repository\TransazioneRepository;

class VersaService
{
   private $transazioneRepository;

   public function __construct(TransazioneRepository $transazioneRepository)
   {
      $this->transazioneRepository = $transazioneRepository;
   }

   public function execute(VersaRequest $request): void
   {
      $transazione = Transazione::versa(
         $this->transazioneRepository->nextIdentity(),
         IdConto::create($request->getIdConto()),
         $request->getSomma()
      );

      $this->transazioneRepository->salva($transazione);
   }
}
