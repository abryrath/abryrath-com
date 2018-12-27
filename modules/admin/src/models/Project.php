<?php

namespace modules\adminmodule\models;

use yii\db\ActiveRecord;
use modules\adminmodule\models\Server;

class Project extends ActiveRecord
{
    public static function tableName()
    {
        return 'abryrath_admin_projects';
    }

    public function getServer()
    {
        return $this->hasOne(Server::class, ['serverId' => 'id']);
    }

    public function getBackups()
    {
        return $this->hasMany(Backup::class, ['projectId' => 'id']);
    }
}
