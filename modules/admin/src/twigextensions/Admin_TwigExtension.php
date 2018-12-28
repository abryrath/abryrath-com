<?php

namespace modules\adminmodule\twigextensions;

use Craft;
use Twig_Extension;
use Twig_SimpleFunction;
use modules\adminmodule\AdminModule;

class Admin_TwigExtension extends Twig_Extension
{
    public function __construct()
    {
        $env = Craft::$app->getView()->getTwig();
        $env->addGlobal('app', AdminModule::$instance);
    }

    public function getFunctions()
    {
        return [
            //new Twig_SimpleFunction('module', [$this->])
        ];
    }
}
