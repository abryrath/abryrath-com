<?php

namespace modules\adminmodule\controllers;

use craft\web\Controller;
use modules\adminmodule\AdminModule;

class BackupController extends Controller
{
    public function actionCreate(int $projectId)
    {
        if ($backup = AdminModule::$instance->backup->create($projectId)) {
            return json_encode([
                'success' => true,
                'backup' => $backup->toArray(),
            ]);
        } else {
            return json_encode([
                'success' => false,
            ]);
        }
    }
}
