<?php


namespace Brace\Assets;


use Brace\Core\BraceApp;
use Brace\Core\BraceModule;
use Phore\Di\Container\Producer\DiValue;

class AssetsModule implements BraceModule
{

    public function register(BraceApp $app)
    {
        $app->define("assets", new DiValue( new AssetSet()));
    }
}