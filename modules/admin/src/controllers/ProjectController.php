<?php

namespace modules\adminmodule\controllers;

use Craft;
use craft\web\Controller;
use modules\adminmodule\models\Project;

class ProjectController extends Controller
{
    public function actionIndex()
    {
        $projects = Project::find()
            ->orderBy('dateCreated')
            ->all();

        return $this->renderTemplate(
            'admin/projects/index',
            [
                'projects' => $projects,
                'newProject' => new Project(),
            ]
        );
    }

    public function actionShow(int $projectId)
    {
        $project = Project::find()->where(['id' => $projectId])->one();
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

    public function actionUpdate(int $projectId)
    {
        $this->requirePostRequest();

        $request = Craft::$app->getRequest();
        $project = Project::find()
            ->where(['id' => $projectId])
            ->one();

        foreach ($request->getBodyParams() as $param => $val) {
            if (isset($project->{$param})) {
                if ($project->{$param} != $val) {
                    $project->{$param} = $val;
                }
            }
        }

        return json_encode([
            'success' => $project->save(),
            'project' => $project,
        ]);
    }
}
