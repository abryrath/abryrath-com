<?php

namespace modules\adminmodule\console\controllers;

use Craft;
use yii\console\Controller;
use modules\adminmodule\AdminModule;
use craft\migrations\m180113_153740_drop_users_archived_column;
use modules\adminmodule\migrations\m181225_143613_alter_backups;
use modules\adminmodule\migrations\m181225_143612_create_backups;

class DefaultController extends Controller
{
    public function actionInstall()
    {
        $install = new m181225_143612_create_backups();
        $install->safeUp();
        $install = new m181225_143613_alter_backups();
        $install->safeUp();
    }

    public function actionUninstall()
    {
        $install = new m181225_143613_alter_backups();
        $install->safeDown();
        $install = new m181225_143612_create_backups();
        $install->safeDown();
    }

    public function actionCheck()
    {
        AdminModule::$instance->backup->checkAllProjects();
    }
}