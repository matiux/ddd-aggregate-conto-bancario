<?php

namespace ContoBancario\Application\Conto\Service;

use ContoBancario\Domain\ContoCorrente\Aggregate\IdConto;
use ContoBancario\Domain\ContoCorrente\Aggregate\Transazione;

final class VersaService extends ContoCorrenteService
{
   public function execute(VersaRequest $request): void
   {
      /** @var IdConto $idConto */
      $idConto = IdConto::create($request->getIdConto());

      $this->findContoCorrenteOrFail($idConto);

      $transazione = Transazione::versa(
         $this->transazioneRepository->nextIdentity(),
         $idConto,
         $request->getSomma()
      );

      $this->transazioneRepository->salva($transazione);
   }
}
