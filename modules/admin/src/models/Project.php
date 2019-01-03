<?php

namespace modules\adminmodule\models;

use modules\adminmodule\models\Server;
use modules\adminmodule\models\Backup;
use yii\db\ActiveRecord;
use \DateTime;

class Project extends ActiveRecord
{
    public static function tableName()
    {
        return 'abryrath_admin_projects';
    }

    public function getServer()
    {
        return $this->hasOne(Server::class, ['id' => 'serverId']);
    }

    public function getBackupServer()
    {
        return $this->hasOne(Server::class, ['id' => 'backupServerId']);
    }

    public function getBackups()
    {
        return $this->hasMany(Backup::class, ['projectId' => 'id']);
    }

    public function getBackupFrequencySeconds()
    {
        $backupFreq = $this->backupFrequency;
        if (is_string($backupFreq)) {
            $backupFreq = json_decode($backupFreq);
        }
        if (is_array($backupFreq)) {
            $backupFreq = json_decode(json_encode($backupFreq));
        }
        //var_dump($backupFreq); die;
        $weeks = $backupFreq->weeks ?? 0;
        $days = $backupFreq->days ?? 0;
        $hours = $backupFreq->hours ?? 0;

        $seconds = (((((($weeks * 7) + $days) * 24) + $hours) * 60) * 60);

        return $seconds;
    }

    public function getBackupCommands(DateTime $date): array
    {
        $backupFile = $this->getBackupFile($date);
        //$date = $date->format('Y-m-d_hia');
        //$backupFile = "{$this->id}-{$date}.tar.gz";

        $server = $this->getServer()->one();
        $backupServer = $this->getBackupServer()->one();

        $sshCommand = $server->sshCommand();
        $scpSource = $server->scpSource();

        $commands = [];

        // 0 - Check if syncdb plugin is installed
        //$cmd = $sshCommand . " 'cd {$this->serverSrcPath} && ./craft | grep -e \"craft-sync-db/sync/sync-db\"'";

        // 1 - Backup DB
        $cmd = $sshCommand . " 'cd {$this->serverSrcPath} && ./craft craft-sync-db/sync/dumpmysql && mv ./storage/backups/databases/db_dump.sql.tar.gz /tmp/db_dump.sql.tar.gz'";
        $commands[] = $cmd;

        // 2 - Backup Dir
        $cmd = $sshCommand . " 'cd /tmp && tar czvf {$backupFile} {$this->serverSrcPath} /tmp/db_dump.sql.tar.gz && rm /tmp/db_dump.sql.tar.gz'";
        $commands[] = $cmd;

        // 3 - Copy files to the backup server
        $backupTarget = $backupServer->scpTarget() . ":{$this->backupServerPath}";
        $cmd = "{$scpSource}:/tmp/{$backupFile} {$backupTarget}/{$backupFile}";
        $commands[] = $cmd;

        return $commands;
    }

    public function getBackupFile(DateTime $date): string
    {
        $date = $date->format('Y-m-d_hia');
        $backupFile = "{$this->id}-{$date}.tar.gz";

        return $backupFile;
    }
}
