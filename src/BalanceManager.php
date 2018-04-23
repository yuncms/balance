<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\balance;

use Yii;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yuncms\balance\models\BalanceTransaction;
use yuncms\balance\models\BalanceTransfer;
use yuncms\user\models\User;

/**
 * Class BalanceManager
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class BalanceManager extends Component
{
    /**
     * 给某账户 + 钱
     * @param integer $userId 账户ID
     * @param float $amount 钱数
     * @param string $action 操作事由
     * @param string $description 操作描述
     * @param integer|string $source 源ID
     * @return string
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function increase($userId, $amount, $action, $description = '', $source = null)
    {
        $user = $this->fetchUserId($userId);
        $balance = bcadd($user->balance, $amount);
        if ($balance < 0) {//计算后如果余额小于0，那么结果不合法。
            return false;
        }
        $transaction = BalanceTransaction::getDb()->beginTransaction();//开始事务
        try {
            /** @var BalanceTransaction $transactionModel */
            $transactionModel = new BalanceTransaction([
                'user_id' => $userId, 'type' => $action, 'description' => $description,
                'source' => $source == null ? $userId : $source, 'amount' => $amount, 'balance' => $balance]);
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
     * @param integer $userId 账户ID
     * @param float $amount 钱数
     * @param string $action 操作事由
     * @param string $description 操作描述
     * @param integer|string $source 源ID
     * @return bool|string
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function decrease($userId, $amount, $action, $description = '', $source = null)
    {
        return $this->increase($userId, -$amount, $action, $description, $source);
    }

    /**
     * 转账
     * @param integer $fromUserId
     * @param integer $toUserId
     * @param float $amount 钱数
     * @param string $description
     * @return \yuncms\db\ActiveRecord|bool
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function transfer($fromUserId, $toUserId, $amount, $description = '')
    {
        $userBalanceTransactionId = $this->decrease($fromUserId, $amount, BalanceTransaction::TYPE_TRANSFER, $description, null);
        if ($userBalanceTransactionId) {
            $recipientBalanceTransactionId = $this->increase($toUserId, $amount, BalanceTransaction::TYPE_TRANSFER, $description, null);
            return BalanceTransfer::create([
                'user_id' => $fromUserId,
                'recipient_id' => $toUserId,
                'amount' => $amount,
                'description' => $description,
                'user_balance_transaction_id' => $userBalanceTransactionId,
                'recipient_balance_transaction_id' => $recipientBalanceTransactionId,
            ]);
        }
        return false;
    }

    /**
     * 获取用户钱包
     * @param integer $userId
     * @return User
     * @throws NotFoundHttpException
     */
    protected function fetchUserId($userId)
    {
        if (($user = User::findOne(['id' => $userId])) != null) {
            return $user;
        } else {
            throw new NotFoundHttpException ('User not found.');
        }
    }
}