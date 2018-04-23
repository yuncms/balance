<?php
return [
    'id'=> 'balance',
    'migrationPath' => '@vendor/yuncms/balance/migrations',
    'translations' => [
        'yuncms/balance' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@vendor/yuncms/balance/messages',
        ],
    ],
    'backend' => [
        'class'=>'yuncms\balance\backend\Module'
    ],
    'frontend' => [
        'class'=>'yuncms\balance\frontend\Module'
    ],
];