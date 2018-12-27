<?php

namespace modules\adminmodule\migrations;

use craft\db\Migration;
use yii\db\Exception as DbException;
use modules\adminmodule\AdminModule;

class m181225_143613_alter_backups extends Migration
{
    public function safeUp()
    {
        $this->addColumn(AdminModule::BACKUPS_TABLE, 'active', $this->boolean()->defaultValue(false));
        $this->alterColumn(AdminModule::PROJECTS_TABLE, 'displayName', $this->string()->notNull()->unique());
    }

    public function safeDown()
    {
        $this->dropColumn(AdminModule::BACKUPS_TABLE, 'active');
        $this->alterColumn(AdminModule::PROJECTS_TABLE, 'displayName', $this->string()->notNull());
    }
}
