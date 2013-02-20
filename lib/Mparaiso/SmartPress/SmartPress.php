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

        $this->rmdir();
        $renderPath = $this["config.render_path"] . "/";
        if (!file_exists($renderPath))
            mkdir($renderPath);
        /**
         *  generate pages
         */
        foreach ($this["pages.templates"] as $filePath) {
            //$filename = new \SplFileInfo($filePath);
            $htmlFilePath = $renderPath . preg_replace("/.twig$/","",$filePath);
            if (!file_exists(dirname($htmlFilePath)))
                mkdir(dirname($htmlFilePath), 0777, true);
            $generated_files[] = $this->generatePage($htmlFilePath, $filePath);
        }
        /**
         * generate posts
         */
        foreach ($this["finder.posts_templates"] as $postTemplatePath) {
            $htmlFilePath = $renderPath .preg_replace("/.twig$/","",$postTemplatePath);
            if (!file_exists(dirname($htmlFilePath))) {
                mkdir(dirname($htmlFilePath), 0777, true);
            }
            $generated_files[] = $this->generatePage($htmlFilePath, $postTemplatePath);
        }
        /**
         *  generate index 
         */
        $indexPath = $this["config.index"];
        $renderedIndexPath = $renderPath . basename($this["config.index"], $this["config.extension"]) . "html";
        $generated_files[] = $this->generatePage($renderedIndexPath, $indexPath);
        $this["generated_files"] = $generated_files;
    }

    /**
     * 
     * @param string $realpath le chemin absolu du fichier à créer
     * @param string $templatepath le chemin relatif du gabarit
     * @return string le chemin absolu du fichier à créer
     */
    protected function generatePage($realpath, $templatepath) {
        $content = $this["twig"]->render($templatepath);
        file_put_contents($realpath, $content);
        return $realpath;
    }

    function register(ServiceProviderInterface $serviceProvider, array $definitions = array()) {
        $serviceProvider->register($this);
        foreach ($definitions as $key => $value) {
            $this[$key] = $value;
        }
        return $this;
    }

}

