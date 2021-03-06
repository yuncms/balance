<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `balance_recharges`.
 */
class m180413_021258_create_balance_recharges_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%balance_recharges}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        //创建充值表
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->unsignedInteger()->notNull()->comment('User Id'),//充值目标  user 对象的  id ，64 位以内。
            'succeeded' => $this->boolean()->defaultValue(false),//是否到账
            'refunded' => $this->boolean()->defaultValue(false),//是否退款
            'amount' => $this->decimal(12, 2)->defaultValue(0),//到账金额
            'user_fee' => $this->decimal(12, 2)->defaultValue(0),//用户手续费
            'charge_id' => $this->string(50),
            'balance_bonus_id' => $this->decimal(12, 2)->defaultValue(0),//充值赠送的余额，不可和  user_fee 同时传，默认 0。
            'balance_transaction_id' => $this->unsignedBigInteger(),//关联的余额明细表ID
            'description' => $this->string(),//附加说明，最多 255 个 Unicode 字符。
            'metadata' => $this->text(),//元数据
            'created_at' => $this->unixTimestamp()->comment('Created At'),//创建时间
            'succeeded_at' => $this->unixTimestamp()->comment('Succeeded At'),//到账时间
        ], $tableOptions);

        $this->addForeignKey('recharges_fk_1', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('recharges_fk_2', $this->tableName, 'balance_transaction_id', '{{%balance_transaction}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('recharges_fk_3', $this->tableName, 'charge_id', '{{%transaction_charges}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
