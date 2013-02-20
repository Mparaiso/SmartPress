<?php

namespace Mparaiso\SmartPress\Command;

use Mparaiso\SmartPress\SmartPress;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DummyCommand extends Command {
    protected $sp;
    function __construct($name=null,SmartPress $sp){
        parent::__construct($name);
        $this->sp = $sp;
    }

    protected function configure() {
        $this->setName('sp:dummy')
        ->setDescription('show smartpress pages');
        #/*->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to gree')
        #->addOption('yell', null, InputOption::VALUE_NONE, "If set,
        #   the task will yellin uppercase letters");*/
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->sp->generate();
        /*$name = $input->getArgument('name');
        if ($name) {
            $text = 'Hello ' . $name;
        } else {
            $text = 'Hello';
        }
        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }
        $output->writeln($text);*/
        $output->writeln(print_r($this->sp["twig.default_vars"],true));
    }

}
