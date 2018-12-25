<?php

namespace modules\adminmodule\controllers;

use Craft;
use craft\web\Controller;

class ProjectController extends Controller
{
    public function actionIndex()
    {
        return "Hello";
    }

    public function actionNewProject()
    {
        Craft::$app->request->require
    }
}
