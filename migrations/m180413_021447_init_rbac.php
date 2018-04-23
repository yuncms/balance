<?php

use yuncms\db\Migration;

/**
 * Class m180413_021427_init_rbac
 */
class m180413_021447_init_rbac extends Migration
{


    public function safeUp()
    {

        $time = time();


        //添加路由
        $this->batchInsert('{{%admin_auth_item}}', ['name', 'type', 'created_at', 'updated_at'], [

            ['/balance/recharge/*', 2, $time, $time],
            ['/balance/recharge/create', 2, $time, $time],
            ['/balance/recharge/delete', 2, $time, $time],
            ['/balance/recharge/index', 2, $time, $time],
            ['/balance/recharge/update', 2, $time, $time],
            ['/balance/recharge/view', 2, $time, $time],

            ['/balance/withdrawal/*', 2, $time, $time],
            ['/balance/withdrawal/create', 2, $time, $time],
            ['/balance/withdrawal/delete', 2, $time, $time],
            ['/balance/withdrawal/index', 2, $time, $time],
            ['/balance/withdrawal/update', 2, $time, $time],
            ['/balance/withdrawal/view', 2, $time, $time],
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
            ['充值创建', '/balance/recharge/create'],
            ['充值删除', '/balance/recharge/delete'],
            ['充值列表', '/balance/recharge/index'],
            ['充值修改', '/balance/recharge/update'],
            ['充值查看', '/balance/recharge/view'],

            ['充值管理', '/balance/recharge/*'],
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
            ['提现创建', '/balance/withdrawal/create'],
            ['提现删除', '/balance/withdrawal/delete'],
            ['提现列表', '/balance/withdrawal/index'],
            ['提现修改', '/balance/withdrawal/update'],
            ['提现查看', '/balance/withdrawal/view'],

            ['提现管理', '/balance/withdrawal/*'],
            ['提现管理', '提现创建'],
            ['提现管理', '提现删除'],
            ['提现管理', '提现查看'],
            ['提现管理', '提现修改'],
            ['提现管理', '提现列表'],
        ]);

        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Super Financial', 'child' => '充值管理']);
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Super Financial', 'child' => '提现管理']);
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Financial', 'child' => '充值管理']);
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Financial', 'child' => '提现管理']);
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
