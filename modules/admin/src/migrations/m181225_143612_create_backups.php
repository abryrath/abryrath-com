<?php

namespace modules\adminmodule\migrations;

use craft\db\Migration;
use modules\adminmodule\AdminModule;
use yii\db\Exception as DbException;

/**
 * m181225_143612_create_backups migration.
 */
class m181225_143612_create_backups extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createServersTable();
        $this->createProjectsTable();
        $this->createBackupsTable();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        try {
            $this->dropForeignKey(AdminModule::FK_PROJECTS_SERVERS_ID, AdminModule::PROJECTS_TABLE);
            $this->dropForeignKey(AdminModule::FK_BACKUPS_PROJECTS_ID, AdminModule::BACKUPS_TABLE);
        } catch (DbException $exception) {
            var_dump($exception);
        }

        try {
            $this->dropTable(AdminModule::SERVERS_TABLE);
            $this->dropTable(AdminModule::BACKUPS_TABLE);
            $this->dropTable(AdminModule::PROJECTS_TABLE);
        } catch (DbException $exception) {
            var_dump($exception);
        }
    }

    private function createServersTable()
    {
        try {
            $this->createTable(AdminModule::SERVERS_TABLE, [
                'id' => $this->primaryKey(),
                'displayName' => $this->string()->notNull(),
                'hostname' => $this->string()->notNull(),
                'username' => $this->string(),
                'port' => $this->integer()->defaultValue(22),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->string()->notNull(),
            ]);
        } catch (DbException $exception) {

        }
    }

    private function createProjectsTable()
    {
        try {
            $this->createTable(AdminModule::PROJECTS_TABLE, [
                'id' => $this->primaryKey(),
                'displayName' => $this->string()->notNull(),
                'serverId' => $this->integer()->notNull(),
                'backupServerId' => $this->integer()->notNull(),
                'serverSrcPath' => $this->string()->notNull(),
                'backupServerPath' => $this->string()->notNull(),
                'backupFrequency' => $this->json(),
                'keepRecords' => $this->integer(),
                'email' => $this->string(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->string()->notNull(),
            ]);
        } catch (DbException $exception) {
            // var_dump($exception);
            //return false;
        }

        try {
            $this->addForeignKey(
                AdminModule::FK_PROJECTS_SERVERS_ID,
                AdminModule::PROJECTS_TABLE,
                'serverId',
                AdminModule::SERVERS_TABLE,
                'id'
            );
        } catch (DbException $e) {
        }

        return true;
    }

    private function createBackupsTable()
    {

        try {
            $this->createTable(AdminModule::BACKUPS_TABLE, [
                'id' => $this->primaryKey(),
                'projectId' => $this->integer()->notNull(),
                'date' => $this->dateTime()->notNull(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->string()->notNull(),
            ]);
        } catch (DbException $exception) {
            //var_dump($exception);
            //return false;
        }

        try {
            $this->addForeignKey(
                AdminModule::FK_BACKUPS_PROJECTS_ID,
                AdminModule::BACKUPS_TABLE,
                'projectId',
                AdminModule::PROJECTS_TABLE,
                'id'
            );
        } catch (DbException $exception) {
            //var_dump($exception);
            //return false;
        }
    }
}
