<?php

namespace modules\adminmodule\console\controllers;

use Craft;
use yii\console\Controller;
use modules\adminmodule\migrations\m181225_143612_create_backups;

class DefaultController extends Controller
{
    public function actionInstall()
    {
        $install = new m181225_143612_create_backups();
        $install->safeUp();
    }

    public function actionUninstall()
    {
        $install = new m181225_143612_create_backups();
        $install->safeDown();
    }
}