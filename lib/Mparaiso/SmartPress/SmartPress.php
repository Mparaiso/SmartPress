<?php

namespace Mparaiso\SmartPress;

use \Pimple;

class SmartPress extends Pimple {

    function __construct(array $values = array()) {
        parent::__construct($values);
        $this->register(new Provider\ConfigServiceProvider);
        $this->register(new Provider\TwigServiceProvider);
        $this->register(new Provider\FinderProvider);
    }

    function rmdir() {
        $dir = new \RecursiveDirectoryIterator($this["config.render_path"]);
        exec("rm -rf " . $this["config.render_path"] . "/*");
    }

    function generate() {
        # for each page in the page folder,
        # generate the html file 
        # then generate the index
        $generated_files = array();
        $this->rmdir();
        // generate pages
        foreach ($this["pages.templates"] as $filePath) {
            $result = $this["twig"]->render($filePath, array_merge($this["twig.default_vars"], array()));
            //$filename = new \SplFileInfo($filePath);
            $renderPath = $this["config.render_path"] . "/";
            if (!file_exists($renderPath))
                mkdir($renderPath);
            $newFilePath = $renderPath . $filePath;
            if (!file_exists(dirname($newFilePath)))
                mkdir(dirname($newFilePath), 0777, true);
            file_put_contents($newFilePath, $result);
            array_push($generated_files, $newFilePath);
        }
        $this["generated_files"] = $generated_files;
        // generate index 
        $indexPath = $this["config.index"] ;
        $result = $this["twig"]->render($indexPath, array("pages" => array_merge($this["twig.default_vars"], array())));
        file_put_contents($this["config.render_path"] . "/" . $this["config.index"] . "." . $this["config.output_extension"], $result);
    }

    function register(ServiceProviderInterface $serviceProvider, array $definitions = array()) {
        $serviceProvider->register($this);
        foreach ($definitions as $key => $value) {
            $this[$key] = $value;
        }
        return $this;
    }

}

