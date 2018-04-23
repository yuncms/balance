<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `transaction_balance_bonus`.
 */
class m180413_014650_create_transaction_balance_bonus_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%transaction_balance_bonus}}';

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
        //创建余额赠送
        $this->createTable($this->tableName, [
            'id' => $this->bigPrimaryKey()->unsigned(),//主ID
            'paid' => $this->boolean()->defaultValue(false),//是否已经赠送
            'user_id' => $this->unsignedInteger()->notNull()->comment('User Id'),//受赠的  user 对象的  id 。
            'amount' => $this->decimal(12, 2)->notNull(),//受赠金额
            'order_no' => $this->string(64)->notNull(),//模块订单号，必须在模块的系统内唯一，64 位以内。
            'description' => $this->string(60),//附加说明，最多 60 个 Unicode 字符。
            'time_paid' => $this->unixTimestamp(),//成功赠送的时间
            'balance_transaction_id' => $this->unsignedBigInteger(),//关联的交易流水ID
            'metadata' => $this->text(),//metadata 参数 数组，一些源数据。
            'created_at' => $this->unixTimestamp()->comment('Created At'),//创建时间
        ], $tableOptions);

        $this->addForeignKey('transaction_balance_bonus_fk_1', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
