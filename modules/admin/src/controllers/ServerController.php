<?php

namespace modules\adminmodule\controllers;

use Craft;
use craft\web\Controller;
use modules\adminmodule\models\Server;

class ServerController extends Controller
{
    public function actionIndex()
    {
        return $this->renderTemplate('admin/servers/index');
    }

    public function actionNewServer()
    {
        $this->requirePostRequest();

        $request = Craft::$app->getRequest();

        $server = new Server();
        $server->displayName = $request->getBodyParam('displayName');
        $server->hostname = $request->getBodyParam('hostname');
        $server->username = $request->getBodyParam('username');
        $server->port = $request->getBodyParam('port');

        $server->save();
    }
}
