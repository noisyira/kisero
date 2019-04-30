<?php
return [
    'components' => [
        'db'=>require(__DIR__ . '/db.php'),
        'cdr'=>require(__DIR__ . '/db_cdr.php'),
        'erz'=>require(__DIR__ . '/db_erz.php'),
        'useroptions'=>require(__DIR__ . '/db_useroptions.php'),
        'gosp'=>require(__DIR__ . '/db_gosp.php'),
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ]
    ],
];
