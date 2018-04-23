<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `transaction_balance_settlement`.
 */
class m180413_020756_create_transaction_balance_settlement_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%transaction_balance_settlement}}';

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
        //余额结算表 当一笔订单设定了余额结算信息参数时，支付完成后，
        //系统将自动将扣除手续费（user_fee）后的支付金额结算到指定的用户余额账户并生成 balance_settlement 对象。
        //通常使用该对象查询一笔或多笔订单余额结算的状态。注意： 结算的入账状态是系统处理的一个中间状态，一般不需要关心。
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->unsignedInteger()->notNull()->comment('User Id'),//结算的  user 对象的  id
            'amount' => $this->decimal(12, 2)->notNull(),//结算金额，包含用户手续费
            'user_fee' => $this->decimal(12, 2)->defaultValue(0),//向结算用户收取的手续费
            'refunded' => $this->boolean()->defaultValue(false),//余额结算金额是否有退款。
            'amount_refunded' => $this->decimal(12, 2)->defaultValue(0),//已退款的余额结算金额，单位分。
            'charge_id' => $this->string(50)->notNull(),//支付号
            'charge_order_no' => $this->string(64),//付款订单号
            'charge_transaction_no' => $this->string(64),//付款流水号
            'failure_msg' => $this->string(),//失败消息
            'status' => $this->unsignedSmallInteger(1)->defaultValue(0),//结算状态。待结算： created （表示余额还未到结算用户余额账户），已入账： credited （表示余额已到账但不可用），已结算： succeeded （表示余额到账且可用）。
            'credited_at' => $this->unixTimestamp()->comment('Credited At'),//入账完成时间。
            'succeeded_at' => $this->unixTimestamp()->comment('Succeeded At'),//结算完成时间。
            'created_at' => $this->unixTimestamp()->comment('Created At'),//创建时间
        ], $tableOptions);

        $this->addForeignKey('transaction_balance_settlement_fk_1', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('transaction_balance_settlement_fk_2', $this->tableName, 'charge_id', '{{%transaction_charges}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
