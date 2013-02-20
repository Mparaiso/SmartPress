<?php

/**
 * ce script permet une prévisualisation du site statique
 */
$autoload = require(__DIR__ . "/../vendor/autoload.php");

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Mparaiso\SmartPress\SmartPress;

$sp = new SmartPress(array("config.root_path" => __DIR__ . "/../src/",));
$app = new Application;
$app["debug"] = true;
$app["sp"] = $sp;
$app->register(new TwigServiceProvider, array(
    "twig.path" => array(__DIR__ . "/../src",),
        )
);
$app["twig"] = $app->share(
        $app->extend("twig", function($twig, $app) {
                    $twig->addGlobal("sp", $app["sp"]["twig.default_vars"]);
                    return $twig;
                }
        )
);
        
$app->match("/{url}", function($url, Application $app) {
            return $app["twig"]->render($url .".twig");
        }
)->value("url", "index.html")->assert("url", "(\S)+");

$app->run();