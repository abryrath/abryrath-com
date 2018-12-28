<?php

namespace modules\adminmodule\models;

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
}
