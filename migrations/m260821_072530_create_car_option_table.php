<?php

use yii\db\Migration;

class m260821_072530_create_car_option_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%car_option}}', [
            'id' => $this->primaryKey(),
            'car_id' => $this->integer()->notNull(),
            'brand' => $this->string(255)->notNull(),
            'model' => $this->string(255)->notNull(),
            'year' => $this->integer()->notNull(),
            'body' => $this->string(255)->notNull(),
            'mileage' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-car_option-car_id', '{{%car_option}}', 'car_id', true);

        $this->addForeignKey(
            'fk-car_option-car_id',
            '{{%car_option}}',
            'car_id',
            '{{%car}}',
            'id',
            'CASCADE',
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-car_option-car_id', '{{%car_option}}');
        $this->dropTable('{{%car_option}}');
    }
}
