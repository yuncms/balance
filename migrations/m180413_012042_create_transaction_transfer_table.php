<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `transaction_transfer`.
 */
class m180413_012042_create_transaction_transfer_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%transaction_transfer}}';

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
        //创建企业付款表 系统内部表
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'type' => $this->string(5)->notNull(),//付款类型，转账到个人用户为 b2c，转账到企业用户为 b2b（wx、wx_pub、wx_lite 和 balance 渠道的企业付款，仅支持 b2c）。
            'channel' => $this->string(64)->notNull()->comment('Channel Identity'),//付款渠道
            'status' => $this->unsignedSmallInteger(1)->defaultValue(0),//付款状态。目前支持 4 种状态：pending: 处理中; paid: 付款成功; failed: 付款失败; scheduled: 待发送。
            'order_no' => $this->string(64)->notNull(),//款使用的商户内部订单号。
            // wx/wx_pub/wx_lite 规定为 1 ~ 32 位不能重复的数字字母组合;
            // alipay 为 1 ~ 64 位不能重复的数字字母组合;
            //unionpay 为 1 ~ 16 位的纯数字;
            //  allinpay 为 20 ~ 40 位不能重复的数字字母组合，必须以签约的通联的商户号开头（建议组合格式：通联商户号 + 时间戳 + 固定位数顺序流水号，不包含 + 号）;
            // jdpay 为 1 ~ 64 位不能重复的数字字母组合；
            // balance 为 1 ~ 64 位不能重复的数字字母组合，支持"-"和"_"。
            'amount' => $this->decimal(12, 2)->notNull(),//付款金额
            'currency' => $this->string(3)->notNull(),//三位 ISO 货币代码，目前仅支持人民币 cny。
            'recipient' => $this->string()->notNull(),//接收者 id，使用微信企业付款到零钱时为用户在  wx 、 wx_pub 及  wx_lite 渠道下的  open_id ，使用企业付款到银行卡时不需要此参数；
            //渠道为  unionpay 时，不需要传该参数；
            //渠道为  alipay 时，若 type 为 b2c，为个人支付宝账号，若 type 为 b2b，为企业支付宝账号；
            //渠道为  jdpay 和  allinpay 时，可不传该参数。
            //渠道为  balance 时，为用户在当前 app 下的用户 id。
            'description' => $this->string(255)->notNull(),//备注信息，最多 255 个 Unicode 字符。
            //渠道为  unionpay 时，最多 99 个 Unicode 字符；
            //渠道为  wx 、 wx_pub 、 wx_lite 时，最多 99 个英文和数字的组合或最多 33 个中文字符，不可以包含特殊字符；
            //渠道为  alipay 和  jdpay 时，最多 100 个 Unicode 字符；
            //渠道为  allinpay 时，最多 30 个 Unicode 字符；
            //渠道为  balance 时，最多 255 个 Unicode 字符。
            'transaction_no' => $this->string(64),//交易流水号，由第三方渠道提供。
            'failure_msg' => $this->string(),//企业付款订单的错误消息的描述。
            'metadata' => $this->text(),//元数据
            'extra' => $this->text(),//附加参数
            'created_at' => $this->unixTimestamp(),
            'transferred_at' => $this->unixTimestamp(),//交易完成时间戳
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
