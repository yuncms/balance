<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\balance\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use yuncms\user\models\User;

/**
 * 余额操作
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Balance extends Model
{
    /**
     * 给某账户 + 钱
     * @param User $user 用户账户
     * @param float $amount 钱数
     * @param string $action 操作事由
     * @param string $description 操作描述
     * @param integer|string $source 源ID
     * @return string
     * @throws \yii\db\Exception
     */
    public static function increase($user, $amount, $action, $description = '', $source = null)
    {
        $balance = bcadd($user->balance, $amount);
        if ($balance < 0) {//计算后如果余额小于0，那么结果不合法。
            return false;
        }
        $transaction = BalanceTransaction::getDb()->beginTransaction();//开始事务
        try {
            /** @var BalanceTransaction $transactionModel */
            $transactionModel = new BalanceTransaction([
                'user_id' => $user->id, 'type' => $action, 'description' => $description,
                'source' => $source == null ? $user->id : $source, 'amount' => $amount, 'balance' => $balance]);
            if ($transactionModel->save() && (bool)$user->updateAttributes(['balance' => $balance])) {
                $transaction->commit();
                return $transactionModel->id;
            } else {
                $transaction->rollBack();
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage(), __METHOD__);
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage(), __METHOD__);
        }
        return false;
    }

    /**
     * 给某账户 - 钱
     * @param User $user 用户账户
     * @param float $amount 钱数
     * @param string $action 操作事由
     * @param string $description 操作描述
     * @param integer|string $source 源ID
     * @return bool|string
     * @throws Exception
     */
    public static function decrease($user, $amount, $action, $description = '', $source = null)
    {
        return static::increase($user, -$amount, $action, $description, $source);
    }

    /**
     * 送钱
     * @param User $user 用户账户
     * @param float $amount
     * @param string $description
     * @return BalanceBonus|\yuncms\db\ActiveRecord
     */
    public static function bonus($user, $amount, $description)
    {
        return BalanceBonus::create(['user_id' => $user->id, 'amount' => $amount, 'description' => $description]);
    }

    /**
     * 充值
     * @param User $user
     * @return BalanceRecharge|\yuncms\db\ActiveRecord
     */
    public static function recharge($user, $amount)
    {
        return BalanceRecharge::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'user_fee' => $user_fee,
            'balance_bonus' => balance_bonus,
            'balance_transaction_id' => '',
            'description' => '',
        ]);
    }

    /**
     * 余额结算
     * @param User $user
     * @return BalanceSettlement|\yuncms\db\ActiveRecord
     */
    public static function settlement($user)
    {
        return BalanceSettlement::create([]);
    }

    /**
     * 转账
     * @param User $fromUser
     * @param User $toUser
     * @param float $amount 钱数
     * @param string $description
     * @return \yuncms\db\ActiveRecord|bool
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public static function transfer($fromUser, $toUser, $amount, $description = '')
    {
        $userBalanceTransactionId = static::decrease($fromUser, $amount, BalanceTransaction::TYPE_TRANSFER, $description, null);
        if ($userBalanceTransactionId) {
            $recipientBalanceTransactionId = static::increase($toUser, $amount, BalanceTransaction::TYPE_TRANSFER, $description, null);
            return BalanceTransfer::create([
                'user_id' => $fromUser,
                'recipient_id' => $toUser,
                'amount' => $amount,
                'description' => $description,
                'user_balance_transaction_id' => $userBalanceTransactionId,
                'recipient_balance_transaction_id' => $recipientBalanceTransactionId,
            ]);
        }
        return false;
    }
}