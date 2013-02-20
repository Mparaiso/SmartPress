<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Mparaiso\SmartPress\Provider;

use Mparaiso\SmartPress\ServiceProviderInterface;
use Mparaiso\SmartPress\SmartPress;
use Symfony\Component\Finder\Finder;

class FinderProvider implements ServiceProviderInterface {

    function register(SmartPress $sp) {
        $sp["pages.finder"] = $sp->share(function($sp) {
                    $finder = new Finder;
                    $finder->files()->in($sp["config.pages_path"])->name("/" . $sp["config.extension"] . "$/");
                    return $finder;
                });
        $sp["pages.templates"] = $sp->share(function($sp) {
                    $templates = [];
                    foreach ($sp["pages.finder"] as $file) {
                        $root = $sp["config"]["default"]["pages"];
                        $templates[] = $root . "/" . $file->getRelativePath() . "/" . $file->getFilename();
                    }
                    return $templates;
                });
        $sp["finder.posts"] = $sp->share(function($sp) {
                    $finder = new Finder;
                    $finder->files()->in($sp["config.posts_path"])->name("/" . $sp["config.extension"] . "$/");
                    return $finder;
                });
        $sp["finder.posts_templates"] = $sp->share(function($sp) {
                    foreach ($sp["finder.posts"] as $file) {
                        $root = $sp["config"]["default"]["posts"];
                        $templates[] = $root."/".$file->getRelativePath()."/".$file->getFilename();
                    }
                    return $templates;
                });
    }

}

?>
