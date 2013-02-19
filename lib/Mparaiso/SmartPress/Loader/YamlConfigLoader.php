<?php

namespace Mparaiso\SmartPress\Loader;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class YamlConfigLoader extends FileLoader {

function load($resource,$type=null){
    $path = $this->locator->locate($resource);
    $configValues= Yaml::parse($path);
    return $configValues;
}
function supports($resource,$type=null){
    return is_string($resource) && "yml"=== pathinfo($resource,PATHINFO_EXTENSION);
}
}