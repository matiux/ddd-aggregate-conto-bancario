<?php

namespace ContoBancario\Infrastructure\Comunication\Console\Symfony\Command;

use ContoBancario\Application\Conto\Service\CreaConto;
use ContoBancario\Application\Conto\Service\CreaContoRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreaContoCorrenteCommand extends Command
{
   private $creaConto;

   public function __construct(CreaConto $creaConto, string $name = null)
   {
      parent::__construct($name);

      $this->creaConto = $creaConto;
   }

   protected function configure()
   {
      $this
         ->setName('banca:conto:crea')
         ->addArgument('titolare', InputArgument::REQUIRED, 'Il titolare del conto')
         ->setDescription('Crea un nuovo conto corrente');
   }

   protected function execute(InputInterface $input, OutputInterface $output)
   {
      $idConto = $this->creaConto->execute(new CreaContoRequest($input->getArgument('titolare')));

      $output->writeln('Contro creato: '. $idConto);
   }
}
