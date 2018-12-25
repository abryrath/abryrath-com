<?php

namespace modules\adminmodule\models;

use yii\db\ActiveRecord;

class Project extends ActiveRecord
{
    public $id;
    public $displayName;
    public $serverSrcPath;
    public $backupFrequency;
    public $keepRecords;
    public $email;

    public static function tableName()
    {
        return '{{abryrath_admin_projets}}';
    }
}