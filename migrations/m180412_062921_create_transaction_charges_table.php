<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `transaction_charges`.
 */
class m180412_062921_create_transaction_charges_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%transaction_charges}}';

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
            'paid' => $this->boolean()->defaultValue(false),//boolean 是否已付款
            'refunded' => $this->boolean()->defaultValue(false),//boolean 是否存在退款信息
            'reversed' => $this->boolean()->defaultValue(false),//boolean 订单是否撤销
            'channel' => $this->string(64)->notNull()->comment('Channel Identity'),//付款渠道
            'order_no' => $this->string(64)->notNull(),//商户订单号，适配每个渠道对此参数的要求，必须在商户的系统内唯一
            'amount' => $this->decimal(12, 2)->notNull(),//订单总金额（必须大于 0)
            'currency' => $this->string(3)->notNull(),//3 位 ISO 货币代码，人民币为  cny 。
            'subject' => $this->string(32)->notNull(),//商品标题，该参数最长为 32 个 Unicode 字符
            'body' => $this->string(128)->notNull(),//商品描述信息，该参数最长为 128 个 Unicode 字符
            'client_ip' => $this->ipAddress(),//发起支付请求客户端的 IP 地址
            'extra' => $this->text(),//特定渠道发起交易时需要的额外参数，以及部分渠道支付成功返回的额外参数
            'time_paid' => $this->unixTimestamp(),//订单支付完成时的 Unix 时间戳。（银联支付成功时间为接收异步通知的时间）
            'time_expire' => $this->unixTimestamp(),//订单失效时间
            'transaction_no' => $this->string(64),//支付渠道返回的交易流水号。
            'amount_refunded' => $this->decimal(12, 2)->notNull()->defaultValue('0.00'),//已退款总金额，单位为对应币种的最小货币单位，例如：人民币为分。
            'failure_code' => $this->string(),//订单的错误码
            'failure_msg' => $this->string(),//订单的错误消息的描述。
            'metadata' => $this->text(),//metadata 参数 数组，一些源数据。
            'credential' => $this->text(),//支付凭证，用于客户端发起支付。
            'description' => $this->string(255),//订单附加说明，最多 255 个 Unicode 字符。
            'created_at' => $this->unixTimestamp(),
        ], $tableOptions);

        $this->addPrimaryKey('transaction_charges_pk', $this->tableName, 'id');

        $this->createIndex('transaction_charges_index_paid', $this->tableName, 'paid');
        $this->createIndex('transaction_charges_index_refunded', $this->tableName, 'refunded');

        $this->addForeignKey('transaction_charges_fk_1', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('transaction_charges_fk_2', $this->tableName, 'channel', '{{%transaction_channels}}', 'identity', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
