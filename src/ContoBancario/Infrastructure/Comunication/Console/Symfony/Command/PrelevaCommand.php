<?php

namespace ContoBancario\Infrastructure\Comunication\Console\Symfony\Command;

use ContoBancario\Application\Conto\Service\PrelevaRequest;
use ContoBancario\Application\Conto\Service\PrelevaService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PrelevaCommand extends Command
{
   private $prelevaService;

   public function __construct(PrelevaService $prelevaService, string $name = null)
   {
      parent::__construct($name);

      $this->prelevaService = $prelevaService;
   }

   protected function configure()
   {
      $this
         ->setName('banca:conto:preleva')
         ->addArgument('id_conto', InputArgument::REQUIRED, 'Id del conto dal quale prelevare')
         ->addArgument('somma', InputArgument::REQUIRED, 'Somma da prelevare')
         ->setDescription('Effettua un prelievo dal conto corrente');
   }

   protected function execute(InputInterface $input, OutputInterface $output)
   {
      $this->prelevaService->execute(
         new PrelevaRequest(
            $input->getArgument('somma'),
            $input->getArgument('id_conto')
         )
      );

      $output->writeln('Prelievo effettuato.');
   }
}
