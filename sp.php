<?php


use Mparaiso\SmartPress\SmartPress;
use Mparaiso\SmartPress\Command\DummyCommand;
use Mparaiso\SmartPress\Command\GenerateCommand;
use Symfony\Component\Console\Application;

require("vendor/autoload.php");
$sp = new SmartPress(array(
    "config.root_path"=>__DIR__."/src/",
    "debug"=>true,
));
$cli = new Application("SmartPression command line interface","0.0.1");
$cli->add(new DummyCommand("SmartPress",$sp));
$cli->add(new GenerateCommand("SmartPress",$sp));

$cli->run();



