<?php

namespace modules\adminmodule\services;

use craft\base\Component;
use DateInterval;
use DateTime;
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

            $nextBackup = $this->getNextScheduledBackup($project->id);
            $now = new DateTime();

            if ($nextBackup < $now) {
                echo "Backup should happen";
            } else {
                echo "Next backup: " . $nextBackup->format('Y-m-d H:i:s');
                echo " (about " . $now->diff($nextBackup)->format('%h') . " hours)";
            }
            echo PHP_EOL;
    
            die;
        }
    }

    public function getNextScheduledBackup(int $projectId): DateTIme
    {
        $project = Project::find($projectId)->one();
        $lastBackup = $project->getBackups()
            ->where(['active' => true])
            ->orderBy('date')
            ->one();
        
        $backupFreqSec = $project->getBackupFrequencySeconds();
        $backupInterval = DateInterval::createFromDateString("{$backupFreqSec} seconds");

        $lastBackupTime = new DateTime($lastBackup->date);
        $nextBackup = $lastBackupTime->add($backupInterval);

        return $nextBackup;

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
        $backup->active = true;
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
