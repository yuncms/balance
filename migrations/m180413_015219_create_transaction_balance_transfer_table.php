<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `transaction_balance_transfer`.
 */
class m180413_015219_create_transaction_balance_transfer_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%transaction_balance_transfer}}';

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
        //创建余额转账表
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->unsignedInteger()->notNull()->comment('User Id'),//转出方的  user 对象的  id 。
            'recipient_id' => $this->unsignedInteger()->notNull(),//接收方的  user 对象的  id 。
            'status' => $this->unsignedSmallInteger(1),//目前值为转账成功： succeeded 。
            'amount' => $this->decimal(12, 2)->notNull(),//接收方收到转账的金额。
            'order_no' => $this->string(64),//商户订单号，适配每个渠道对此参数的要求，必须在商户的系统内唯一。
            'user_fee' => $this->decimal(12, 2),//向发起转账的用户额外收取的手续费,且值需小于 amount。
            'user_balance_transaction_id' => $this->unsignedBigInteger(),//转账关联的转出方  balance_transaction 对象的  id 。
            'recipient_balance_transaction' => $this->unsignedBigInteger(),//转账关联的接收方  balance_transaction 对象的  id 。
            'description' => $this->string(60),//附加说明，最多 60 个 Unicode 字符。
            'metadata' => $this->text(),//metadata 参数 数组，一些源数据。
            'created_at' => $this->integer()->notNull()->comment('Created At'),//创建时间
        ], $tableOptions);

        $this->addForeignKey('transaction_balance_transfer_fk_1', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
