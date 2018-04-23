<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `transaction_balance_transaction`.
 */
class m180413_013158_create_transaction_balance_transaction_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%transaction_balance_transaction}}';

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
        //创建余额明细表
        $this->createTable($this->tableName, [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'user_id' => $this->unsignedInteger()->notNull()->comment('User Id'),
            'amount' => $this->decimal(12, 2)->notNull(),//订单总金额（必须大于 0），单位为对应币种的最小货币单位，人民币为分
            'balance' => $this->decimal(12, 2),//该笔交易发生后，用户的余额。
            'description' => $this->string(255),
            'source' => $this->unsignedBigInteger()->notNull()->comment('Source Id'),//关联对象的 ID
            'type' => $this->string(30)->notNull()->comment('Transaction Type'),//交易类型 交易类型。
            //充值： recharge ，充值退款： recharge_refund ，
            //充值退款失败： recharge_refund_failed ，
            //提现申请： withdrawal ，提现失败： withdrawal_failed ，
            //提现撤销： withdrawal_revoked ，支付/收款： payment ，
            //退款/收到退款： payment_refund ，转账/收到转账： transfer ，
            //赠送： receipts_extra ，分润/收到分润： royalty ，
            //入账： credited ，入账退款： credited_refund ，
            //入账退款失败： credited_refund_failed 。
            'created_at' => $this->unixTimestamp()->comment('Created At'),//创建时间
        ], $tableOptions);

        $this->addForeignKey('transaction_balance_transaction_fk_1', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
