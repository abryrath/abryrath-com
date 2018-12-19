<?php
/**
 * admin module for Craft CMS 3.x
 *
 * Admin stuff for my site
 *
 * @link      https://github.com/abryrath
 * @copyright Copyright (c) 2018 Abry Rath <abryrath@gmail.com>
 */

namespace modules\adminmodule\assetbundles\AdminModule;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Abry Rath <abryrath@gmail.com>
 * @package   AdminModule
 * @since     0.1.0
 */
class AdminModuleAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@modules/adminmodule/assetbundles/adminmodule/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'main.js',
        ];

        $this->css = [
            'css/AdminModule.css',
        ];

        parent::init();
    }
}
