<?php

namespace ContoBancario\Infrastructure\Comunication\Console\Symfony\Command;

use ContoBancario\Application\Conto\Service\VersaRequest;
use ContoBancario\Application\Conto\Service\VersaService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VersaCommand extends Command
{
   private $versaService;

   public function __construct(VersaService $versaService, string $name = null)
   {
      parent::__construct($name);

      $this->versaService = $versaService;
   }

   protected function configure()
   {
      $this
         ->setName('banca:conto:versa')
         ->addArgument('id_conto', InputArgument::REQUIRED, 'Id del conto in cui versare')
         ->addArgument('somma', InputArgument::REQUIRED, 'Somma da versare')
         ->setDescription('Effettua un versamento nel conto corrente');
   }

   protected function execute(InputInterface $input, OutputInterface $output)
   {
      $this->versaService->execute(
         new VersaRequest(
            $input->getArgument('somma'),
            $input->getArgument('id_conto')
         )
      );

      $output->writeln('Versamento effettuato.');
   }
}
