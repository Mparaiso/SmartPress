<?php

namespace Mparaiso\SmartPress\Utils;

class ShellCommands {

    static function rmdir($dir) {
        return exec("rm -rf $dir");
    }

    static function cp($from, $to) {
        return exec("cp -r $from $to");
    }

}