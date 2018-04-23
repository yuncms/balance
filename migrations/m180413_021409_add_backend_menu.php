<?php

use yii\db\Query;
use yuncms\db\Migration;

/**
 * Class m180413_021409_add_backend_menu
 */
class m180413_021409_add_backend_menu extends Migration
{
    /*
     * @var string the table name.
     */
    public $tableName;

    public function safeUp()
    {
        $this->insert('{{%admin_menu}}', [
            'name' => '渠道管理',
            'parent' => 7,
            'route' => '/transaction/channel/index',
            'icon' => 'fa-rmb',
            'sort' => NULL,
            'data' => NULL
        ]);
        $id = (new Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '渠道管理', 'parent' => 7])->scalar($this->getDb());
        $this->batchInsert('{{%admin_menu}}', ['name', 'parent', 'route', 'visible', 'sort'], [
            ['创建渠道', $id, '/transaction/channel/create', 0, NULL],
            ['渠道查看', $id, '/transaction/channel/view', 0, NULL],
            ['更新渠道', $id, '/transaction/channel/update', 0, NULL],
        ]);

        $this->insert('{{%admin_menu}}', [
            'name' => '支付管理',
            'parent' => 7,
            'route' => '/transaction/charge/index',
            'icon' => 'fa-rmb',
            'sort' => NULL,
            'data' => NULL
        ]);
        $id = (new Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '支付管理', 'parent' => 7])->scalar($this->getDb());
        $this->batchInsert('{{%admin_menu}}', ['name', 'parent', 'route', 'visible', 'sort'], [
            ['创建支付', $id, '/transaction/charge/create', 0, NULL],
            ['支付查看', $id, '/transaction/charge/view', 0, NULL],
            ['更新支付', $id, '/transaction/charge/update', 0, NULL],
            ['删除支付', $id, '/transaction/charge/delete', 0, NULL],
        ]);

        $this->insert('{{%admin_menu}}', [
            'name' => '退款管理',
            'parent' => 7,
            'route' => '/transaction/refund/index',
            'icon' => 'fa-rmb',
            'sort' => NULL,
            'data' => NULL
        ]);
        $id = (new Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '退款管理', 'parent' => 7])->scalar($this->getDb());
        $this->batchInsert('{{%admin_menu}}', ['name', 'parent', 'route', 'visible', 'sort'], [
            ['创建退款', $id, '/transaction/refund/create', 0, NULL],
            ['退款查看', $id, '/transaction/refund/view', 0, NULL],
            ['更新退款', $id, '/transaction/refund/update', 0, NULL],
        ]);

        $this->insert('{{%admin_menu}}', [
            'name' => '充值管理',
            'parent' => 7,
            'route' => '/transaction/recharge/index',
            'icon' => 'fa-rmb',
            'sort' => NULL,
            'data' => NULL
        ]);
        $id = (new Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '充值管理', 'parent' => 7])->scalar($this->getDb());
        $this->batchInsert('{{%admin_menu}}', ['name', 'parent', 'route', 'visible', 'sort'], [
            ['创建充值', $id, '/transaction/recharge/create', 0, NULL],
            ['充值查看', $id, '/transaction/recharge/view', 0, NULL],
            ['更新充值', $id, '/transaction/recharge/update', 0, NULL],
        ]);

        $this->insert('{{%admin_menu}}', [
            'name' => '提现管理',
            'parent' => 7,
            'route' => '/transaction/withdrawal/index',
            'icon' => 'fa-rmb',
            'sort' => NULL,
            'data' => NULL
        ]);
        $id = (new Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '提现管理', 'parent' => 7])->scalar($this->getDb());
        $this->batchInsert('{{%admin_menu}}', ['name', 'parent', 'route', 'visible', 'sort'], [
            ['创建提现', $id, '/transaction/withdrawal/create', 0, NULL],
            ['提现查看', $id, '/transaction/withdrawal/view', 0, NULL],
            ['更新提现', $id, '/transaction/withdrawal/update', 0, NULL],
        ]);
    }

    public function safeDown()
    {
        $id = (new Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '渠道管理', 'parent' => 7])->scalar($this->getDb());
        $this->delete('{{%admin_menu}}', ['parent' => $id]);
        $this->delete('{{%admin_menu}}', ['id' => $id]);

        $id = (new Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '支付管理', 'parent' => 7])->scalar($this->getDb());
        $this->delete('{{%admin_menu}}', ['parent' => $id]);
        $this->delete('{{%admin_menu}}', ['id' => $id]);

        $id = (new Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '退款管理', 'parent' => 7])->scalar($this->getDb());
        $this->delete('{{%admin_menu}}', ['parent' => $id]);
        $this->delete('{{%admin_menu}}', ['id' => $id]);

        $id = (new Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '充值管理', 'parent' => 7])->scalar($this->getDb());
        $this->delete('{{%admin_menu}}', ['parent' => $id]);
        $this->delete('{{%admin_menu}}', ['id' => $id]);

        $id = (new Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '提现管理', 'parent' => 7])->scalar($this->getDb());
        $this->delete('{{%admin_menu}}', ['parent' => $id]);
        $this->delete('{{%admin_menu}}', ['id' => $id]);
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180412_093133_add_backend_menu cannot be reverted.\n";

        return false;
    }
    */
}
