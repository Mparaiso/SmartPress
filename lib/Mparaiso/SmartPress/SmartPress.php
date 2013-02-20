<?php

namespace Mparaiso\SmartPress;

use \Pimple;
use Mparaiso\SmartPress\Utils\ShellCommands;

!defined("DS") AND define("DS", DIRECTORY_SEPARATOR);

class SmartPress extends Pimple {

    function __construct(array $values = array()) {
        parent::__construct($values);
        $this->register(new Provider\ConfigServiceProvider);
        $this->register(new Provider\TwigServiceProvider);
        $this->register(new Provider\FinderServiceProvider);
    }

    function generateAssets() {
        /**
         * copier le dossier assets
         */
        if(!file_exists(dirname($this["config.asset_destination_path"])))
            mkdir(dirname($this["config.asset_destination_path"]),0777,true);
        ShellCommands::cp($this["config.asset_path"], $this["config.asset_destination_path"]);
    }

    function generatePages($renderPath) {
        if (!file_exists($renderPath))
            mkdir($renderPath);
        /**
         *  generate pages
         */
        foreach ($this["pages.templates"] as $filePath) {
            //$filename = new \SplFileInfo($filePath);
            $htmlFilePath = $renderPath . preg_replace("/.twig$/", "", $filePath);
            if (!file_exists(dirname($htmlFilePath)))
                mkdir(dirname($htmlFilePath), 0777, true);
            $generated_files[] = $this->generatePage($htmlFilePath, $filePath);
        }
        return $generated_files;
    }

    function generatePosts($renderPath) {
        /**
         * generate posts
         */
        foreach ($this["finder.posts_templates"] as $postTemplatePath) {
            $htmlFilePath = $renderPath . preg_replace("/.twig$/", "", $postTemplatePath);
            if (!file_exists(dirname($htmlFilePath))) {
                mkdir(dirname($htmlFilePath), 0777, true);
            }
            $generated_files[] = $this->generatePage($htmlFilePath, $postTemplatePath);
        }
        return $generated_files;
    }

    function generateIndex($renderPath) {
        /**
         *  generate index 
         */
        $indexPath = $this["config.index"];
        $renderedIndexPath = $renderPath . basename($this["config.index"], $this["config.extension"]) . "html";
        $generated_files[] = $this->generatePage($renderedIndexPath, $indexPath);
        return $generated_files;
    }

    function generate() {
        # for each page in the page folder,
        # generate the html file 
        # then generate the index
        ShellCommands::rmdir($this["config.render_path"] . DS);
        $renderPath = $this["config.render_path"] . DS;
        $this["generated_files"] = array_merge($this->generatePages($renderPath), $this->generatePosts($renderPath), $this->generateIndex($renderPath));
        $this->generateAssets();
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

