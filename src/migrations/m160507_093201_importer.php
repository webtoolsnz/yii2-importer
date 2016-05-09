<?php

use yii\db\Migration;

class m160507_093201_importer extends Migration
{
    public function safeUp()
    {
        $this->createTable('import', [
            'id' => $this->primaryKey(11),
            'status_id' => $this->smallInteger(3)->notNull(),
            'import_model' => $this->string(255)->notNull(),
            'abort' => $this->smallInteger(1)->defaultValue(0),
            'progress' => $this->integer(3)->notNull(),
            'started_at' => $this->dateTime(),
            'created_at' => $this->dateTime()->notNull(),
            'error_count' => $this->integer(11)->notNull(),
            'filename' => $this->text()->notNull(),
            'data' => 'MEDIUMBLOB NOT NULL',
            'total_rows' => $this->integer(11)->notNull(),
            'processed_rows' => $this->integer(11)->notNull(),
        ], 'ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable('import');
    }
}
