<?php

use yuncms\db\Migration;

/**
 * Class m180413_021427_init_rbac
 */
class m180413_021427_init_rbac extends Migration
{


    public function safeUp()
    {

        $time = time();

        // 财务人员角色
        $this->batchInsert('{{%admin_auth_item}}', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            ['Super Financial', 1, '超级财务', 'RouteRule', $time, $time],
            ['Financial', 1, '财务', 'RouteRule', $time, $time],
        ]);

        //添加路由
        $this->batchInsert('{{%admin_auth_item}}', ['name', 'type', 'created_at', 'updated_at'], [
            ['/transaction/channel/*', 2, $time, $time],
            ['/transaction/channel/create', 2, $time, $time],
            ['/transaction/channel/delete', 2, $time, $time],
            ['/transaction/channel/index', 2, $time, $time],
            ['/transaction/channel/update', 2, $time, $time],
            ['/transaction/channel/view', 2, $time, $time],

            ['/transaction/charge/*', 2, $time, $time],
            ['/transaction/charge/create', 2, $time, $time],
            ['/transaction/charge/delete', 2, $time, $time],
            ['/transaction/charge/index', 2, $time, $time],
            ['/transaction/charge/update', 2, $time, $time],
            ['/transaction/charge/view', 2, $time, $time],

            ['/transaction/refund/*', 2, $time, $time],
            ['/transaction/refund/create', 2, $time, $time],
            ['/transaction/refund/delete', 2, $time, $time],
            ['/transaction/refund/index', 2, $time, $time],
            ['/transaction/refund/update', 2, $time, $time],
            ['/transaction/refund/view', 2, $time, $time],

            ['/transaction/recharge/*', 2, $time, $time],
            ['/transaction/recharge/create', 2, $time, $time],
            ['/transaction/recharge/delete', 2, $time, $time],
            ['/transaction/recharge/index', 2, $time, $time],
            ['/transaction/recharge/update', 2, $time, $time],
            ['/transaction/recharge/view', 2, $time, $time],

            ['/transaction/withdrawal/*', 2, $time, $time],
            ['/transaction/withdrawal/create', 2, $time, $time],
            ['/transaction/withdrawal/delete', 2, $time, $time],
            ['/transaction/withdrawal/index', 2, $time, $time],
            ['/transaction/withdrawal/update', 2, $time, $time],
            ['/transaction/withdrawal/view', 2, $time, $time],
        ]);

        $this->batchInsert('{{%admin_auth_item}}', ['name', 'type', 'rule_name', 'created_at', 'updated_at'], [
            ['支付渠道管理', 2, 'RouteRule', $time, $time],
            ['支付渠道列表', 2, 'RouteRule', $time, $time],
            ['支付渠道查看', 2, 'RouteRule', $time, $time],
            ['支付渠道创建', 2, 'RouteRule', $time, $time],
            ['支付渠道删除', 2, 'RouteRule', $time, $time],
            ['支付渠道修改', 2, 'RouteRule', $time, $time],
        ]);
        $this->batchInsert('{{%admin_auth_item_child}}', ['parent', 'child'], [
            ['支付渠道创建', '/transaction/channel/create'],
            ['支付渠道删除', '/transaction/channel/delete'],
            ['支付渠道列表', '/transaction/channel/index'],
            ['支付渠道修改', '/transaction/channel/update'],
            ['支付渠道查看', '/transaction/channel/view'],

            ['支付渠道管理', '/transaction/channel/*'],
            ['支付渠道管理', '支付渠道创建'],
            ['支付渠道管理', '支付渠道删除'],
            ['支付渠道管理', '支付渠道查看'],
            ['支付渠道管理', '支付渠道修改'],
            ['支付渠道管理', '支付渠道列表'],
        ]);

        $this->batchInsert('{{%admin_auth_item}}', ['name', 'type', 'rule_name', 'created_at', 'updated_at'], [
            ['支付管理', 2, 'RouteRule', $time, $time],
            ['支付创建', 2, 'RouteRule', $time, $time],
            ['支付删除', 2, 'RouteRule', $time, $time],
            ['支付列表', 2, 'RouteRule', $time, $time],
            ['支付修改', 2, 'RouteRule', $time, $time],
            ['支付查看', 2, 'RouteRule', $time, $time],
        ]);
        $this->batchInsert('{{%admin_auth_item_child}}', ['parent', 'child'], [
            ['支付创建', '/transaction/charge/create'],
            ['支付删除', '/transaction/charge/delete'],
            ['支付列表', '/transaction/charge/index'],
            ['支付修改', '/transaction/charge/update'],
            ['支付查看', '/transaction/charge/view'],
            ['支付管理', '/transaction/charge/*'],
            ['支付管理', '支付创建'],
            ['支付管理', '支付删除'],
            ['支付管理', '支付列表'],
            ['支付管理', '支付修改'],
            ['支付管理', '支付查看'],
        ]);

        $this->batchInsert('{{%admin_auth_item}}', ['name', 'type', 'rule_name', 'created_at', 'updated_at'], [
            ['退款管理', 2, 'RouteRule', $time, $time],
            ['退款列表', 2, 'RouteRule', $time, $time],
            ['退款查看', 2, 'RouteRule', $time, $time],
            ['退款创建', 2, 'RouteRule', $time, $time],
            ['退款删除', 2, 'RouteRule', $time, $time],
            ['退款修改', 2, 'RouteRule', $time, $time],
        ]);
        $this->batchInsert('{{%admin_auth_item_child}}', ['parent', 'child'], [
            ['退款创建', '/transaction/refund/create'],
            ['退款删除', '/transaction/refund/delete'],
            ['退款列表', '/transaction/refund/index'],
            ['退款修改', '/transaction/refund/update'],
            ['退款查看', '/transaction/refund/view'],

            ['退款管理', '/transaction/refund/*'],
            ['退款管理', '退款创建'],
            ['退款管理', '退款删除'],
            ['退款管理', '退款查看'],
            ['退款管理', '退款修改'],
            ['退款管理', '退款列表'],
        ]);

        $this->batchInsert('{{%admin_auth_item}}', ['name', 'type', 'rule_name', 'created_at', 'updated_at'], [
            ['充值管理', 2, 'RouteRule', $time, $time],
            ['充值列表', 2, 'RouteRule', $time, $time],
            ['充值查看', 2, 'RouteRule', $time, $time],
            ['充值创建', 2, 'RouteRule', $time, $time],
            ['充值删除', 2, 'RouteRule', $time, $time],
            ['充值修改', 2, 'RouteRule', $time, $time],
        ]);
        $this->batchInsert('{{%admin_auth_item_child}}', ['parent', 'child'], [
            ['充值创建', '/transaction/recharge/create'],
            ['充值删除', '/transaction/recharge/delete'],
            ['充值列表', '/transaction/recharge/index'],
            ['充值修改', '/transaction/recharge/update'],
            ['充值查看', '/transaction/recharge/view'],

            ['充值管理', '/transaction/recharge/*'],
            ['充值管理', '充值创建'],
            ['充值管理', '充值删除'],
            ['充值管理', '充值查看'],
            ['充值管理', '充值修改'],
            ['充值管理', '充值列表'],
        ]);

        $this->batchInsert('{{%admin_auth_item}}', ['name', 'type', 'rule_name', 'created_at', 'updated_at'], [
            ['提现管理', 2, 'RouteRule', $time, $time],
            ['提现列表', 2, 'RouteRule', $time, $time],
            ['提现查看', 2, 'RouteRule', $time, $time],
            ['提现创建', 2, 'RouteRule', $time, $time],
            ['提现删除', 2, 'RouteRule', $time, $time],
            ['提现修改', 2, 'RouteRule', $time, $time],
        ]);
        $this->batchInsert('{{%admin_auth_item_child}}', ['parent', 'child'], [
            ['提现创建', '/transaction/withdrawal/create'],
            ['提现删除', '/transaction/withdrawal/delete'],
            ['提现列表', '/transaction/withdrawal/index'],
            ['提现修改', '/transaction/withdrawal/update'],
            ['提现查看', '/transaction/withdrawal/view'],

            ['提现管理', '/transaction/withdrawal/*'],
            ['提现管理', '提现创建'],
            ['提现管理', '提现删除'],
            ['提现管理', '提现查看'],
            ['提现管理', '提现修改'],
            ['提现管理', '提现列表'],
        ]);

        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Super Financial', 'child' => '支付渠道管理']);
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Super Financial', 'child' => '支付管理']);
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Super Financial', 'child' => '充值管理']);
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Super Financial', 'child' => '提现管理']);
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Super Financial', 'child' => '退款管理']);
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Financial', 'child' => '支付渠道列表']);
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Financial', 'child' => '支付渠道查看']);
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Financial', 'child' => '支付管理']);
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Financial', 'child' => '充值管理']);
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Financial', 'child' => '提现管理']);
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Financial', 'child' => '退款管理']);
    }

    public function safeDown()
    {

    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180412_093134_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
