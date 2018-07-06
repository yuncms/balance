<?php

use yuncms\db\Migration;

/**
 * Class m180706_081452_update_settlement_table
 */
class m180706_081452_update_settlement_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%balance_settlement}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'data', $this->text());
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'data');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180706_081452_update_settlement_table cannot be reverted.\n";

        return false;
    }
    */
}
