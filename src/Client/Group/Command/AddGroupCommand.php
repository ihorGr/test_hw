<?php

declare(strict_types=1);

namespace App\Client\Group\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddGroupCommand extends Command
{

    public function __construct()
    {
        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setDescription('Add a new group')
            ->setName('task-hw:add-group')
            ->setHelp('This command allows you to create a group...')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the group.')
            /*->addArgument(
                self::SYNC_KEY_FIELD,
                InputArgument::OPTIONAL,
                'Service syn_key'
            )*/;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //TODO:
        // get data
        // transform to object
        // send to server by http-client

        $name = $input->getArgument('name');     //TODO: implement

        $output->writeln('Group name: '.$name);
    }

}