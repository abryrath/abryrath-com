<?php

namespace modules\adminmodule\models;

use yii\db\ActiveRecord;

class Server extends ActiveRecord
{
    public static function tableName()
    {
        return 'abryrath_admin_servers';
    }
}
