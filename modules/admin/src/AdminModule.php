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
use craft\console\Application as ConsoleApplication;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\TemplateEvent;
use craft\i18n\PhpMessageSource;
use craft\web\twig\variables\Cp;
use craft\web\UrlManager;
use craft\web\View;
use modules\adminmodule\assetbundles\adminmodule\AdminModuleAsset;
use modules\adminmodule\twigextensions\Admin_TwigExtension;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\base\Module;

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
    const SERVERS_TABLE = 'abryrath_admin_servers';
    const PROJECTS_TABLE = 'abryrath_admin_projects';
    const BACKUPS_TABLE = 'abryrath_admin_backups';

    const FK_PROJECTS_SERVERS_ID = 'dk_projects_servers_id';
    const FK_BACKUPS_PROJECTS_ID = 'fk_backups_projects_id';

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

        Craft::$app->view->registerTwigExtension(new Admin_TwigExtension());

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
            'server' => \modules\adminmodule\services\ServerService::class,
        ]);

        Event::on(
            Cp::class,
            Cp::EVENT_REGISTER_CP_NAV_ITEMS,
            function (RegisterCpNavItemsEvent $event) {
                $event->navItems[] = [
                    'url' => 'admin',
                    'label' => 'Admin',
                    'icon' => '@modules/adminmodule/assetbundles/adminmodule/dist/img/AdminModule-icon.svg',
                    'subnav' => [
                        'home' => ['label' => 'Home', 'url' => 'admin'],
                        'projects' => ['label' => 'Projects', 'url' => 'admin/projects'],
                        'servers' => ['label' => 'Servers', 'url' => 'admin/servers'],
                    ],
                ];
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['admin/servers'] = 'admin/server/index';
                $event->rules['admin/servers/create'] = 'admin/server/create';
                $event->rules['admin/projects'] = 'admin/project/index';
                $event->rules['admin/projects/create'] = 'admin/project/create';
                $event->rules['admin/projects/remove/<projectId>'] = 'admin/project/remove';
                $event->rules['admin/projects/update/<projectId>'] = 'admin/project/update';
                $event->rules['admin/projects/<projectId>'] = 'admin/project/show';
                $event->rules['admin/projects/<projectId>/backups/create'] = 'admin/backup/create';
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
