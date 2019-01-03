<?php

namespace modules\adminmodule\models;

use DateTime;
use modules\adminmodule\models\Project;
use yii\db\ActiveRecord;

class Backup extends ActiveRecord
{
    public static function tableName()
    {
        return 'abryrath_admin_backups';
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'projectId']);
    }

    public function getRemoveCommands(): array
    {
        $project = $this->getProject()->one();
        $backupFile = $project->getBackupFile(new DateTime($this->date));
        $backupServer = $project->getBackupServer()->one();

        $sshCommand = $backupServer->sshCommand();

        $commands = [];

        $cmd = $sshCommand . " 'cd {$project->backupServerPath} && rm {$backupFile}";
        $commands[] = $cmd;

        return $commands;
    }
}
