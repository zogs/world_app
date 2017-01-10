<?php

namespace Zogs\WorldBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportSQLCommand extends ContainerAwareCommand
{

	private $errors = array();
	private $success = array();

    protected function configure()
    {
        $this
            ->setName('world:import:sql')
            ->setDescription('Importe un fichier sql')
            ->addArgument('database', InputArgument::REQUIRED, 'Database name ?')
            ->addArgument('file', InputArgument::REQUIRED, 'File path ?')
            ->addOption('folder', null, InputOption::VALUE_NONE, 'Import all files in dir')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        
        $importer = $this->getContainer()->get('world.importer');
        $importer->setDatabase($input->getArgument('database'));

         if(true == $input->getOption('folder')){
            $res = $importer->importAll($file);
         }
         else {
            $res = $importer->import($file);
         }

        $output->writeln($res);
    }

}