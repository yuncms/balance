<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `transaction_refunds`.
 */
class m180412_062949_create_transaction_refunds_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%transaction_refunds}}';

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
            'id' => $this->string(50)->notNull()->comment('ID'),
            'user_id' => $this->unsignedInteger()->notNull()->comment('User Id'),
            'amount' => $this->decimal(12, 2)->notNull(),//退款金额大于 0, 必须小于等于可退款金额，默认为全额退款。
            'succeed' => $this->boolean()->defaultValue(false),//退款是否成功。
            'status' => $this->unsignedSmallInteger(1)->defaultValue(0),//退款状态（目前支持三种状态: pending: 处理中; succeeded: 成功; failed: 失败）。
            'description' => $this->string(255)->notNull(),//退款详情，最多 255 个 Unicode 字符。
            'failure_code' => $this->string(),//退款的错误码，详见 错误 中的错误码。
            'failure_msg' => $this->string(),//退款消息的描述。
            'charge_id' => $this->string(50)->notNull(),//支付  charge 对象的  id
            'charge_order_no' => $this->string(64),//商户订单号，这边返回的是  charge 对象中的  order_no 参数。
            'transaction_no' => $this->string(64),//支付渠道返回的交易流水号，部分渠道返回该字段为空。
            'funding_source' => $this->string(20),//微信及 QQ 类退款资金来源。取值范围： unsettled_funds ：使用未结算资金退款； recharge_funds ：微信-使用可用余额退款，QQ-使用可用现金账户资金退款。
            //注：默认值  unsettled_funds ，该参数对于微信渠道的退款来说仅适用于微信老资金流商户使用，包括  wx 、 wx_pub 、 wx_pub_qr 、 wx_lite 、 wx_wap 、 wx_pub_scan 六个渠道；
            //新资金流退款资金默认从基本账户中扣除。该参数仅在请求退款，传入该字段时返回。
            'metadata' => $this->text(),
            'extra' => $this->text(),
            'time_succeed' => $this->unixTimestamp(),//退款成功的时间，用 Unix 时间戳表示。
            'created_at' => $this->unixTimestamp(),
        ], $tableOptions);

        $this->addPrimaryKey('transaction_refunds_pk', $this->tableName, 'id');
        $this->createIndex('transaction_refunds_index_status', $this->tableName, 'status');
        $this->createIndex('transaction_refunds_index_succeed', $this->tableName, 'succeed');

        $this->addForeignKey('transaction_refunds_fk_1', $this->tableName, 'charge_id', '{{%transaction_charges}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('transaction_refunds_fk_2', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
