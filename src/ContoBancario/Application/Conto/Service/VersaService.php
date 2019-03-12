<?php

namespace ContoBancario\Application\Conto\Service;

use ContoBancario\Domain\ContoCorrente\Aggregate\IdConto;

final class VersaService extends ContoCorrenteService
{
   public function execute(VersaRequest $request): void
   {
      /** @var IdConto $idConto */
      $idConto = IdConto::create($request->getIdConto());

      $contoCorrente = $this->findContoCorrenteOrFail($idConto);

      $contoCorrente->versa(
         $this->transazioneRepository->nextIdentity(),
         $request->getSomma()
      );

      $this->contoCorrenteRepository->salva($contoCorrente);
   }
}
