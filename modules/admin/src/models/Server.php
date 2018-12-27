<?php

namespace modules\adminmodule\models;

use yii\db\ActiveRecord;

class Server extends ActiveRecord
{
    public static function tableName()
    {
        return 'abryrath_admin_servers';
    }

    public function sshCommand(): string
    {
        $cmd = $this->scpSource(true);

        return $cmd;
    }

    public function scpSource(bool $ssh = false): string
    {
        $cmd = '';
        if ($ssh) {
            $cmd .= 'ssh ';
        } else {
            $cmd = 'scp ';
        }

        if ($this->port) {
            $portFlag = $ssh ? '-p' : '-P';
            $cmd .= "{$portFlag} {$this->port} ";
        }
        if ($this->username) {
            $cmd .= "{$this->username}@";
        }
        $cmd .= "{$this->hostname}";
        if ($ssh) {
            $cmd .= ' ';
        }

        return $cmd;
    }

    public function scpTarget(): string
    {
        $cmd = '';
        if ($this->username) {
            $cmd .= "{$this->username}@";
        }
        $cmd .= "{$this->hostname}";

        return $cmd;
    }
}
