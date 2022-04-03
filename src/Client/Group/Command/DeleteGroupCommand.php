<?php

declare(strict_types=1);

namespace App\Client\Group\Command;

use App\Client\Group\Request\DeleteGroupRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteGroupCommand extends Command
{
    private const FIELD = 'field';

    public function __construct()
    {
        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setDescription('Edit existed group')
            ->setName('task-hw:delete-group')
            ->setHelp('This command allows you to delete a group...')
            ->addArgument('value', InputArgument::REQUIRED, 'The value by which the deletion occurs')
            ->addOption(
                static::FIELD,
                'tbl',
                InputOption::VALUE_REQUIRED,
                'Field for deletion'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $value = $input->getArgument('value');

        $field = $input->getOption('field');

        $request = new DeleteGroupRequest($field, $value);

        // $response = client->deleteGroup($request);    //TODO: implement

        $output->writeln('Field: '.$field);
        $output->writeln('Group name: '.$value);
    }


}