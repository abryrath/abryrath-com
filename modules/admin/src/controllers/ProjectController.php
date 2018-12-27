<?php

namespace modules\adminmodule\controllers;

use Craft;
use craft\web\Controller;
use modules\adminmodule\models\Project;
use modules\adminmodule\models\Server;

class ProjectController extends Controller
{
    public function actionIndex()
    {
        $projects = Project::find()
            ->orderBy('dateCreated')
            ->all();

        $servers = Server::find()
            ->orderBy('dateCreated')
            ->all();

        $servers = array_map(function ($server) {
            return [
                'key' => $server->id,
                'value' => $server->id,
                'label' => $server->displayName,
            ];
        }, $servers);

        return $this->renderTemplate(
            'admin/projects/index',
            [
                'projects' => $projects,
                'servers' => $servers,
            ]
        );
    }

    public function actionNewProject()
    {
        $this->requirePostRequest();

        $request = Craft::$app->getRequest();

        $project = new Project();
        $project->displayName = $request->getBodyParam('displayName');
        $project->serverId = $request->getBodyParam('serverId');
        $project->serverSrcPath = $request->getBodyParam('serverSrcPath');
        $project->backupFrequency = $request->getBodyParam('backupFrequency');
        $project->keepRecords = $request->getBodyParam('keepRecords');
        $project->email = $request->getBodyParam('email');

        if ($project->save()) {
            return json_encode([
                'success' => true,
                'project' => $project->toArray(),
            ]);
        }
    }
}
