<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `transaction_channels`.
 */
class m180412_044511_create_transaction_channels_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%transaction_channels}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned()->comment('Id'),
            'identity' => $this->string(64)->notNull()->unique()->comment('Channel Identity'),
            'name' => $this->string(64)->notNull()->comment('Channel Name'),
            'title' => $this->string(64)->notNull()->comment('Channel Title'),
            'description' => $this->string(255)->notNull()->comment('Channel Description'),
            'className' => $this->string()->comment('Channel ClassName'),
            'configuration' => $this->text()->comment('Channel Config'),
            'status' => $this->smallInteger(1)->defaultValue(1)->comment('Status'),
            'created_at' => $this->integer()->notNull()->comment('Created At'),//创建时间
            'updated_at' => $this->integer()->notNull()->comment('Updated At'),//更新时间
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
