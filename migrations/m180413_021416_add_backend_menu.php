<?php

use yii\db\Query;
use yuncms\db\Migration;

/**
 * Class m180413_021409_add_backend_menu
 */
class m180413_021416_add_backend_menu extends Migration
{
    /*
     * @var string the table name.
     */
    public $tableName;

    public function safeUp()
    {


        $this->insert('{{%admin_menu}}', [
            'name' => '充值管理',
            'parent' => 7,
            'route' => '/balance/recharge/index',
            'icon' => 'fa-rmb',
            'sort' => NULL,
            'data' => NULL
        ]);
        $id = (new Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '充值管理', 'parent' => 7])->scalar($this->getDb());
        $this->batchInsert('{{%admin_menu}}', ['name', 'parent', 'route', 'visible', 'sort'], [
            ['创建充值', $id, '/balance/recharge/create', 0, NULL],
            ['充值查看', $id, '/balance/recharge/view', 0, NULL],
            ['更新充值', $id, '/balance/recharge/update', 0, NULL],
        ]);

        $this->insert('{{%admin_menu}}', [
            'name' => '提现管理',
            'parent' => 7,
            'route' => '/balance/withdrawal/index',
            'icon' => 'fa-rmb',
            'sort' => NULL,
            'data' => NULL
        ]);
        $id = (new Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '提现管理', 'parent' => 7])->scalar($this->getDb());
        $this->batchInsert('{{%admin_menu}}', ['name', 'parent', 'route', 'visible', 'sort'], [
            ['创建提现', $id, '/balance/withdrawal/create', 0, NULL],
            ['提现查看', $id, '/balance/withdrawal/view', 0, NULL],
            ['更新提现', $id, '/balance/withdrawal/update', 0, NULL],
        ]);
    }

    public function safeDown()
    {
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
