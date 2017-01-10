<?php

namespace Zogs\DatabaseToolBundle\Command;

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
            ->setName('db:import:sql')
            ->setDescription('Importe un fichier sql')
            ->addArgument('file', InputArgument::REQUIRED, 'File path ?')
            ->addArgument('database', InputArgument::OPTIONAL, 'Database name ?')
            ->addOption('folder', null, InputOption::VALUE_NONE, 'Import dir ?')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force ?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        
        $importer = $this->getContainer()->get('db.importer.sql');

        $database = $input->getArgument('database');
        if($database) {
            $importer->setDatabase($database);
        }

        if(true == $input->getOption('force')) {
            $importer->addOption('force');
        }


         if(true == $input->getOption('folder')){
            $res = $importer->importAll($file);
         }
         else {
            $res = $importer->import($file);
         }

        $output->writeln($res);
    }

}