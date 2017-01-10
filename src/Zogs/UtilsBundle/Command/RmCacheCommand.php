<?php

namespace Zogs\UtilsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\StringInput;

class RmCacheCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
            ->setName('rm:cache')
            ->setDescription('Remove cache folders')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $res = shell_exec('sudo rm -rf ../../../app/cache/dev');

        $output->writeln('<comment>'.$res.'</comment>');
        $output->writeln('<info>Cache removed !</info>');

    }

}