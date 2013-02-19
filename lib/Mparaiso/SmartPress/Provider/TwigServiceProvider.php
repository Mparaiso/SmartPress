<?php

namespace Mparaiso\SmartPress\Provider;

use Mparaiso\SmartPress\SmartPress;
use Mparaiso\SmartPress\ServiceProviderInterface;

/**
 * FR : gère la configuration de twig
 */
class TwigServiceProvider implements ServiceProviderInterface {

    function register(SmartPress $sp) {
        # FR : configuration de twig
        $sp["twig.options"] = $sp->share(function($sp) {
                    return array(
                        "cache" => $sp["config.cache_path"],
                    );
                }
        );
        # FR : le twig loader , on utilise des fichiers par défaut
        $sp["twig.loader"] = $sp->share(function($sp) {
                    return new \Twig_Loader_filesystem($sp["config.root_path"]);
                }
        );
        # FR : l'envirronment Twig
        $sp["twig"] = $sp->share(function($sp) {
                    $twig =  new \Twig_Environment($sp["twig.loader"]
                            , $sp["twig.options"]);
                    $twig->addGlobal("sp", $sp["twig.default_vars"]);
                    return $twig;
                }
        );
        # FR : variables globales à injecter chaque fois qu'un template est rendu
        $sp["twig.default_vars"] = $sp->share(function($sp) {
                    $vars = array();
                    $vars["pages"] = $sp["twig.pages.parser"]($sp["pages.templates"]);
                    return $vars;
                }
        );
        $sp["twig.pages.parser"] = $sp->protect(function($pageList) {
                    return self::generatePageArray($pageList);
                });
        $sp["twig.posts.parser"] = $sp->protect(function($postList) {
                    return self::generatePostArray($postList);
                });
        //$sp["twig.extensions"]=
    }

    /**
     * FR : génére la variable des posts
     * @param array $postList
     * @return array
     */
    function generatePostArray(array $postList) {
        $result = array();
        //foreach($sp["posts."])
        return $postList;
    }

    /**
     * FR : transforme une liste de chemins de fichiers en une arborescence de fichiers
     * @param array $pageList
     * @return array the directory tree
     */
    function generatePageArray(array $pageList) {
        $result = array();
        foreach ($pageList as $templatePath) {
            $pathParts = explode("/", $templatePath);
            $index = 0;
            $root = &$result;
            foreach ($pathParts as $part) {
                $index+=1;
                if ($index === count($pathParts)) {
                    $pathinfo = pathinfo($templatePath);
                    $root[] = array(
                        "url" => $pathinfo["dirname"] . "/" . $pathinfo["filename"],
                        "label" => preg_replace("/-{1}/", " ", preg_replace("/\.\S+$/", "", $pathinfo["filename"])),
                    );
                    break;
                }
                if (strlen(trim($part)) <= 0)
                    continue;
                if (!isset($root[$part]))
                    $root[$part] = array();
                $root = &$root[$part];
            }
        }
        return $result;
    }

}