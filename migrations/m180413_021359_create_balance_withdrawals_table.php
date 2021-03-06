<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `balance_withdrawals`.
 */
class m180413_021359_create_balance_withdrawals_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%balance_withdrawals}}';

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
        //提现申请表
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->unsignedInteger()->notNull()->comment('User Id'),
            'status' => $this->unsignedSmallInteger(1)->defaultValue(0),//提现状态，已申请： created ，处理中： pending ，完成： succeeded ，失败： failed ，取消： canceled 。
            'amount' => $this->decimal(12, 2)->notNull(),//提现扣除的余额
            'channel' => $this->string(64)->notNull(),//提现使用的付款渠道
            'settle_account_id' => $this->unsignedInteger(),//提现使用的settle_account
            'metadata' => $this->text(),//元数据
            'extra' => $this->text(),//渠道参数
            'created_at' => $this->unixTimestamp()->comment('Created At'),//创建时间
            'canceled_at' => $this->unixTimestamp()->comment('Updated At'),//取消时间
            'succeeded_at' => $this->unixTimestamp()->comment('Updated At'),//成功时间
        ], $tableOptions);

        //状态加索引
        $this->createIndex('balance_withdrawals_index_1', $this->tableName, ['status']);
        $this->addForeignKey('balance_withdrawals_fk_1', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('balance_withdrawals_fk_2', $this->tableName, 'settle_account_id', '{{%user_settle_account}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
