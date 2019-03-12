<?php

namespace ContoBancario\Application\Conto\Service;

use ContoBancario\Domain\ContoCorrente\Aggregate\IdConto;
use ContoBancario\Domain\ContoCorrente\Aggregate\Transazione;

final class PrelevaService extends ContoCorrenteService
{
   public function execute(PrelevaRequest $request)
   {
      /** @var IdConto $idConto */
      $idConto = IdConto::create($request->getIdConto());

      $this->findContoCorrenteOrFail($idConto);

      $transazione = Transazione::preleva(
         $this->transazioneRepository->nextIdentity(),
         $idConto,
         $request->getSomma()
      );

      $this->transazioneRepository->salva($transazione);
   }
}
