<?php

namespace Zogs\DatabaseToolBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SizeCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
        ->setName('db:size')
        ->setDescription('Renvoi la taille de la base')
        ->addArgument('database', InputArgument::REQUIRED, 'Name of the database')
        //->addArgument('database', InputArgument::OPTIONAL, 'Database name ?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $connexion = $this->getContainer()->get('doctrine.orm.pgsql_entity_manager')->getConnection();

        $database = $input->getArgument('database');  

        $sql = "SELECT pg_size_pretty(pg_database_size('".$database."')) as size;";





        $q = $connexion->prepare($sql);
        $q->execute();
        $res = $q->fetch();

        $output->writeln($res['size']);
      
    }

}