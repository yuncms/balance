<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\balance\rest\controllers;

use yuncms\web\ActiveController;
use yuncms\balance\rest\models\BalanceTransaction;

/**
 * 余额交易记录
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class TransactionController extends ActiveController
{
    public $modelClass = BalanceTransaction::class;

}