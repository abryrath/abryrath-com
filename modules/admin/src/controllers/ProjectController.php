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

    public function actionShow(int $id)
    {
        $project = Project::find()->where(['id' => $id])->one();
        if (!$project) {
            echo "Invalid project id";
            die;
        }

        return $this->renderTemplate('admin/projects/entry', [
            'project' => $project,
        ]);
    }

    public function actionNewProject()
    {
        $this->requirePostRequest();

        $request = Craft::$app->getRequest();

        $project = new Project();
        $project->displayName = $request->getBodyParam('displayName');
        $project->serverId = $request->getBodyParam('serverId');
        $project->serverSrcPath = $request->getBodyParam('serverSrcPath');
        $project->backupServerId = $request->getBodyParam('backupServerId');
        $project->backupServerPath = $request->getBodyParam('backupServerPath');
        $project->backupFrequency = $request->getBodyParam('backupFrequency');
        $project->keepRecords = $request->getBodyParam('keepRecords');
        $project->email = $request->getBodyParam('email');

        if ($project->save()) {
            return json_encode([
                'success' => true,
                'project' => $project->toArray(),
            ]);
        }

        return json_encode([
            'success' => false,
        ]);
    }
}
