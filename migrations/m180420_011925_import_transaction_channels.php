<?php

use yuncms\db\Migration;

/**
 * Class m180420_011925_import_transaction_channels
 */
class m180420_011925_import_transaction_channels extends Migration
{

    public function safeUp()
    {
        $time = time();
        $this->batchInsert('{{%transaction_channels}}', ['identity', 'name', 'title', 'description', 'className', 'created_at', 'updated_at'], [
            //微信
            ['wechat', '微信', '微信App支付', '微信App支付', 'yuncms\transaction\channels\wechat\App', $time, $time],
            ['wechat_scan', '微信', '微信公众号支付', '微信公众号支付', 'yuncms\transaction\channels\wechat\Scan', $time, $time],
            ['wechat_pub', '微信', '微信公众号支付', '微信公众号支付', 'yuncms\transaction\channels\wechat\Pub', $time, $time],
            ['wechat_wap', '微信', '微信H5支付', '微信H5支付', 'yuncms\transaction\channels\wechat\Wap', $time, $time],
            ['wechat_lite', '微信', '微信小程序支付', '微信小程序支付', 'yuncms\transaction\channels\wechat\Lite', $time, $time],
            ['wechat_pub_qr', '微信', '微信公众号扫码支付', '微信公众号扫码支付', 'yuncms\transaction\channels\wechat\PubQr', $time, $time],
            ['wechat_pub_scan', '微信', '微信公众号刷卡支付', '微信公众号刷卡支付', 'yuncms\transaction\channels\wechat\PubScan', $time, $time],

            //支付宝
            ['alipay', '支付宝', '支付宝App支付', '支付宝App支付', 'yuncms\transaction\channels\alipay\App', $time, $time],
            ['alipay_wap', '支付宝', '支付宝手机网站支付', '支付宝手机网站支付', 'yuncms\transaction\channels\alipay\Wap', $time, $time],
            ['alipay_qr', '支付宝', '支付宝当面付', '支付宝当面付', 'yuncms\transaction\channels\alipay\Qr', $time, $time],
            ['alipay_scan', '支付宝', '支付宝条码支付', '支付宝条码支付', 'yuncms\transaction\channels\alipay\Scan', $time, $time],
            ['alipay_pc_direct', '支付宝', '支付宝电脑网站支付', '支付宝电脑网站支付', 'yuncms\transaction\channels\alipay\PcDirect', $time, $time],

            //QQ钱包
            ['qpay', 'QQ钱包', 'QQ 钱包App支付', 'QQ 钱包 App 支付', 'yuncms\transaction\channels\qpay\App', $time, $time],
            ['qpay_pub', 'QQ钱包', 'QQ 钱包公众号支付', 'QQ 钱包公众号支付', 'yuncms\transaction\channels\qpay\Pub', $time, $time],

            //京东支付
            ['jdpay_wap', '京东支付', '京东手机网页支付', '京东手机网页支付', 'yuncms\transaction\channels\jdpay\Wap', $time, $time],

            //银联支付
            ['unionpay', '银联支付', '银联App支付', '银联支付，即银联 App 支付（2015 年 1 月 1 日后的银联新商户使用。）', 'yuncms\transaction\channels\unionpay\App', $time, $time],
            ['unionpay_pc', '银联支付', '银联PC支付', '银联网关支付，即银联 PC 网页支付', 'yuncms\transaction\channels\unionpay\Pc', $time, $time],
            ['unionpay_wap', '银联支付', '银联手机网页支付', '银联手机网页支付（2015 年 1 月 1 日后的银联新商户使用。）', 'yuncms\transaction\channels\unionpay\Wap', $time, $time],

            //余额支付
            ['balance', '余额支付', '余额支付', '余额支付', 'yuncms\transaction\channels\balance\Balance', $time, $time],
        ]);
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180420_011925_import_transaction_channels cannot be reverted.\n";

        return false;
    }
    */
}
