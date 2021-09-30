<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%import}}`.
 */
class m210721_091251_add_user_id_column_to_import_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%import}}', 'user_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%import}}', 'user_id');
    }
}
