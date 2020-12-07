<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%import}}`.
 */
class m201207_165346_create_import_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%import}}', [
            'id' => $this->primaryKey(),
            'store_id' => $this->integer(),
            'filename' => $this->string(),
            'status' => $this->integer(),
            'success_count' => $this->integer(),
            'failed_count' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%import}}');
    }
}
