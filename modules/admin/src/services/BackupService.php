<?php

namespace modules\adminmodule\services;

use craft\base\Component;
use DateTime;
use DateInterval;
use modules\adminmodule\AdminModule;
use modules\adminmodule\models\Backup;
use modules\adminmodule\models\Project;

class BackupService extends Component
{
    public function checkAllProjects()
    {
        $projects = Project::find()->all();

        foreach ($projects as $project) {
            echo "Checking project: {$project->displayName}" . PHP_EOL;
            $backups = $project->getBackups()
                ->where(['active' => true])
                ->orderBy('date')
                ->all();

            $recordsToKeep = $project->keepRecords;
            if (count($backups) > $recordsToKeep) {
                echo "Deleting oldest backups for {$project->displayName}" . PHP_EOL;
                $this->deleteOldBackups($project);
            }

            $backupFreqSec = $project->getBackupFrequencySeconds();
            $backupInterval = DateInterval::createFromDateString("{$backupFreqSec} seconds");

            $lastBackup = $backups[0];
            $lastBackupTime = new DateTime($lastBackup->date);
            $nextBackup = $lastBackupTime->add($backupInterval);

            if ($nextBackup < new DateTime()) {
                echo "Backup should happen";
            } else {
                echo "Next backup: " . $nextBackup->format('Y-m-d H:i:s');
            }
            echo PHP_EOL;
            
            die;
        }
    }

    public function create(int $projectId): ?Backup
    {
        $project = Project::find($projectId)->one();
        $log = [];

        $date = (new DateTime());
        foreach ($project->getBackupCommands($date) as $command) {
            $output = [];
            if (!$this->exec($command, $output)) {

                var_dump($output);
                die;
            }

            $log[] = $output;
        }

        $backup = new Backup();
        $backup->projectId = $projectId;
        $backup->date = $date->format('Y-m-d H:i:s');
        if (!$backup->save()) {
            return null;
        }

        return $backup;
    }

    private function exec(string $command, array &$output = []): bool
    {
        $resultVar = null;

        exec($command, $output, $resultVar);

        if (intval($resultVar) !== 0) {
            return false;
        }

        return true;
    }
}
