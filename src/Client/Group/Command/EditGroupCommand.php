<?php

declare(strict_types=1);

namespace App\Client\Group\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EditGroupCommand extends Command
{

    public function __construct()
    {
        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setDescription('Edit existed group')
            ->setName('task-hw:edit-group')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the group.')
            ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('foo');     //TODO: implement
    }


}