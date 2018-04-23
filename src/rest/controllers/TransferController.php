<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\balance\rest\controllers;

use yuncms\rest\ActiveController;
use yuncms\balance\rest\models\BalanceTransfer;

/**
 * 清分记录
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class TransferController extends ActiveController
{
    public $modelClass = BalanceTransfer::class;
}