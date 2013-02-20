<?php

namespace Mparaiso\SmartPress\Provider;

use Mparaiso\SmartPress\ServiceProviderInterface;
use Symfony\Component\Config\FileLocator;
use Mparaiso\SmartPress\Loader\YamlConfigLoader;
use Symfony\Component\Config\ConfigCache;
use Mparaiso\SmartPress\SmartPress;

!defined("DS") AND define("DS",DIRECTORY_SEPARATOR);

class ConfigServiceProvider implements ServiceProviderInterface {

    function register(SmartPress $sp) {
        $sp["config.matcher_cache"] = $sp->share(function($sp) {
                    return new ConfigCache($sp["config.cache_path"], true);
                });
        $sp["config.file"] = "config.yml";
        $sp["config.extension"] = $sp->share(function($sp) {
                    return $sp["config"]["default"]["extension"];
                });
        $sp["config.locator"] = $sp->share(function($sp) {
                    return new FileLocator($sp["config.root_path"] . DS."config".DS);
                });
        $sp["config.loader"] = $sp->share(function($sp) {
                    return new YamlConfigLoader($sp["config.locator"]);
                });
        $sp["config.cache_path"] = $sp->share(function($sp) {
                    return $sp["config.root_path"] . DS . $sp["config"]["default"]["cache"];
                });
        $sp["config.index"] = $sp->share(function($sp) {
                    return $sp["config"]["default"]["index"];
                });
        $sp["config.pages_path"] = $sp->share(function($sp) {
                    return $sp["config.root_path"] . DS . $sp["config"]["default"]["pages"];
                });

        $sp["config.posts_path"] = $sp->share(function($sp) {
                    return $sp["config.root_path"] . DS . $sp["config"]["default"]["posts"];
                });

        $sp["config.render_path"] = $sp->share(function($sp) {
                    return $sp["config.root_path"] . DS."..".DS."build";
                }
        );
        $sp["config.output_extension"] = $sp->share(function($sp) {
                    return $sp["config"]["default"]["output_extension"];
                });
        $sp["config.asset_path"]=$sp->share(function($sp){
            return $sp["config.root_path"].DS.$sp["config"]["default"]["assets"];
        });
        $sp["config.asset_destination_path"]=$sp->share(function($sp){
            return   $sp["config.render_path"].DS.$sp["config"]["default"]["assets"];
        });
        $sp["config"] = $sp->share(function($sp) {
                    return $sp["config.loader"]->load($sp["config.file"]);
                }
        );
    }

}