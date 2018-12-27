<?php

namespace modules\adminmodule\migrations;

use craft\db\Migration;
use yii\db\Exception as DbException;

/**
 * m181225_143612_create_backups migration.
 */
class m181225_143612_create_backups extends Migration
{
    const SERVERS_TABLE = 'abryrath_admin_servers';
    const PROJECTS_TABLE = 'abryrath_admin_projects';
    const BACKUPS_TABLE = 'abryrath_admin_backups';

    const FK_PROJECTS_SERVERS_ID = 'dk_projects_servers_id';
    const FK_BACKUPS_PROJECTS_ID = 'fk_backups_projects_id';

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
            $this->dropForeignKey(self::FK_PROJECTS_SERVERS_ID, self::PROJECTS_TABLE);
            $this->dropForeignKey(self::FK_BACKUPS_PROJECTS_ID, self::BACKUPS_TABLE);
        } catch (DbException $exception) {
            var_dump($exception);
        }

        try {
            $this->dropTable(self::SERVERS_TABLE);
            $this->dropTable(self::BACKUPS_TABLE);
            $this->dropTable(self::PROJECTS_TABLE);
        } catch (DbException $exception) {
            var_dump($exception);
        }
    }

    private function createServersTable()
    {
        try {
            $this->createTable(self::SERVERS_TABLE, [
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
            $this->createTable(self::PROJECTS_TABLE, [
                'id' => $this->primaryKey(),
                'displayName' => $this->string()->notNull(),
                'serverId' => $this->integer()->notNull(),
                'serverSrcPath' => $this->string()->notNull(),
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
                self::FK_PROJECTS_SERVERS_ID,
                self::PROJECTS_TABLE,
                'serverId',
                self::SERVERS_TABLE,
                'id'
            );
        } catch (DbException $e) {
        }

        return true;
    }

    private function createBackupsTable()
    {

        try {
            $this->createTable(self::BACKUPS_TABLE, [
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
                self::FK_BACKUPS_PROJECTS_ID,
                self::BACKUPS_TABLE,
                'projectId',
                self::PROJECTS_TABLE,
                'id'
            );
        } catch (DbException $exception) {
            //var_dump($exception);
            //return false;
        }
    }
}
