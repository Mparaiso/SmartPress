<?php

namespace Mparaiso\SmartPress\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Mparaiso\SmartPress\SmartPress;

class DummyCommand extends Command {

    protected $sp;

    function __construct($name, SmartPress $sp) {
        parent::__construct($name);
        $this->sp = $sp;
    }

    protected function configure() {
        $this->setName('generate')
                ->setDescription('Generate static website');
        /* ->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to gree')
          ->addOption('yell', null, InputOption::VALUE_NONE, "If set,
          the task will yellin uppercase letters"); */
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->sp->generate();
        $output->writeln("Done !");
    }

}