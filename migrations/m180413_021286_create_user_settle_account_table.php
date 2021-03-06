<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `user_settle_account.
 */
class m180413_021286_create_user_settle_account_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%user_settle_account}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        //用户结算账户表
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->unsignedInteger()->notNull()->comment('User Id'),
            'channel' => $this->string(64)->notNull()->comment('Channel Identity'),//结算账号渠道名称
            'recipient' => $this->text(),//脱敏的结算账号接收者信息，详情参见请求参数 recipient 部分。
        ], $tableOptions);

        $this->addForeignKey('user_account_fk_1', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
