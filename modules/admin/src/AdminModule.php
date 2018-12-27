<?php
/**
 * admin module for Craft CMS 3.x
 *
 * Admin stuff for my site
 *
 * @link      https://github.com/abryrath
 * @copyright Copyright (c) 2018 Abry Rath <abryrath@gmail.com>
 */

namespace modules\adminmodule;

use Craft;
use craft\web\View;
use yii\base\Event;
use yii\base\Module;
use craft\web\UrlManager;
use craft\events\TemplateEvent;
use craft\i18n\PhpMessageSource;
use craft\web\twig\variables\Cp;
use yii\base\InvalidConfigException;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterTemplateRootsEvent;
use craft\console\Application as ConsoleApplication;
use modules\adminmodule\assetbundles\adminmodule\AdminModuleAsset;
use craft\events\RegisterUrlRulesEvent;

/**
 * Class AdminModule
 *
 * @author    Abry Rath <abryrath@gmail.com>
 * @package   AdminModule
 * @since     0.1.0
 *
 */
class AdminModule extends Module
{
    public static $instance;

    public function __construct($id, $parent = null, array $config = [])
    {
        Craft::setAlias('@modules/adminmodule', $this->getBasePath());
        if (Craft::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'modules\adminmodule\console\controllers';
        } else {
            $this->controllerNamespace = 'modules\adminmodule\controllers';
        }

        // Translation category
        $i18n = Craft::$app->getI18n();
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (!isset($i18n->translations[$id]) && !isset($i18n->translations[$id . '*'])) {
            $i18n->translations[$id] = [
                'class' => PhpMessageSource::class,
                'sourceLanguage' => 'en-US',
                'basePath' => '@modules/adminmodule/translations',
                'forceTranslation' => true,
                'allowOverrides' => true,
            ];
        }

        // Base template directory
        Event::on(
            View::class,
            View::EVENT_REGISTER_CP_TEMPLATE_ROOTS,
            function (RegisterTemplateRootsEvent $e) {
                if (is_dir($baseDir = $this->getBasePath() . DIRECTORY_SEPARATOR . 'templates')) {
                    $e->roots[$this->id] = $baseDir;
                }
            }
        );

        // Set this as the global instance of this module class
        static::setInstance($this);

        parent::__construct($id, $parent, $config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$instance = $this;

        if (Craft::$app->getRequest()->getIsCpRequest()) {
            Event::on(
                View::class,
                View::EVENT_BEFORE_RENDER_TEMPLATE,
                function (TemplateEvent $event) {
                    try {
                        Craft::$app->getView()->registerAssetBundle(AdminModuleAsset::class);
                    } catch (InvalidConfigException $e) {
                        Craft::error(
                            'Error registering AssetBundle - ' . $e->getMessage(),
                            __METHOD__
                        );
                    }
                }
            );
        }

        $this->setComponents([
            'backup' => \modules\adminmodule\services\BackupService::class,
        ]);

        Event::on(
            Cp::class,
            Cp::EVENT_REGISTER_CP_NAV_ITEMS,
            function (RegisterCpNavItemsEvent $event) {
                $event->navItems[] = [
                    'url' => 'admin/projects',
                    'label' => 'Projects',
                    'icon' => '@modules/adminmodule/assetbundles/adminmodule/dist/img/AdminModule-icon.svg',
                ];
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['admin/servers'] = 'admin/server/index';
                $event->rules['admin/servers/new'] = 'admin/server/new-server';
                $event->rules['admin/projects'] = 'admin/project/index';
                $event->rules['admin/projects/new'] = 'admin/project/new-project';
                $event->rules['admin/projects/<id>'] = 'admin/project/show';
            }
        );

        Craft::info(
            Craft::t(
                'admin',
                '{name} module loaded',
                ['name' => 'admin']
            ),
            __METHOD__
        );
    }
}
