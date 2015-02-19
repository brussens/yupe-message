<?php
/**
 * Module configuration file.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@nativeweb.ru>
 * @link http://nativeweb.ru
 * @copyright 2014 Native Web.
 * @package yupe.modules.message.install
 * @since 0.1α
 *
 */
return [
    'module'=>[
        'class'=>'application.modules.message.MessageModule'
    ],
    'import'=>[],
    'component'=>[],
    'rules'=>[
        '/messages/inbox' => 'message/message/inbox',
        '/messages/outbox' => 'message/message/outbox',
        '/messages/spam' => 'message/message/spam',
        '/message/compose' => 'message/message/compose',
        '/message/<id:\d+>' => 'message/message/view',
        '/message/<id:\d+>/remove' => 'message/message/delete',
        '/message/<id:\d+>/spam/mark' => 'message/message/spamMark',
        '/message/<id:\d+>/spam/unmark' => 'message/message/spamUnMark',
    ],
];