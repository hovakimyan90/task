<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%store_product}}`.
 */
class m201127_095041_create_store_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%store_product}}', [
            'id' => $this->primaryKey(),
            'store_id' => $this->integer(11),
            'upc' => $this->string(),
            'title' => $this->string(),
            'price' => $this->float(),
        ]);

        $this->addForeignKey(
            'fk-store-id',
            'store_product',
            'store_id',
            'store',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%store_product}}');
    }
}
