<?php

namespace ContoBancario\Application\Conto\Service;

use ContoBancario\Domain\ContoCorrente\Aggregate\IdConto;

final class PrelevaService extends ContoCorrenteService
{
   public function execute(PrelevaRequest $request)
   {
      /** @var IdConto $idConto */
      $idConto = IdConto::create($request->getIdConto());

      $contoCorrente = $this->findContoCorrenteOrFail($idConto);

      $contoCorrente->preleva(
         $this->transazioneRepository->nextIdentity(),
         $request->getSomma()
      );

      $this->contoCorrenteRepository->salva($contoCorrente);
   }
}
