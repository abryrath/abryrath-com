<?php

namespace modules\adminmodule\migrations;

use craft\db\Migration;

/**
 * m181225_143612_create_backups migration.
 */
class m181225_143612_create_backups extends Migration
{
    const PROJECTS_TABLE = 'abryrath_admin_projects';
    const BACKUPS_TABLE = 'abryrath_admin_table';
    const FK_BACKUPS_PROJECTS_ID = 'fk_backups_projects_id';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createProjectsTable();
        $this->createBackupsTable();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        try {
            $this->dropForeignKey(self::FK_BACKUPS_PROJECTS_ID, self::BACKUPS_TABLE);
        } catch (yii\db\Exception $exception) {
            var_dump($exception);
        }

        try {
            $this->dropTable(self::PROJECTS_TABLE);
        } catch (yii\db\Exception $exception) {
            var_dump($exception);
        }
        
        try {
            $this->dropTable(self::BACKUPS_TABLE);
        } catch (yii\db\Exception $exception) {
            var_dump($exception);
        }
    }

    private function createProjectsTable()
    {
        try {
            $this->createTable(self::PROJECTS_TABLE, [
                'id' => $this->primaryKey(),
                'displayName' => $this->string()->notNull(),
                'serverSrcPath' => $this->string()->notNull(),
                'backupFrequency' => $this->json(),
                'keepRecords' => $this->integer(),
                'email' => $this->string(),
            ]);
        } catch (yii\db\Exception $exception) {
            // var_dump($exception);
            //return false;
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
            ]);
        } catch (yii\db\Exception $exception) {
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
        } catch (yii\db\Exception $exception) {
            //var_dump($exception);
            //return false;
        }
    }
}
