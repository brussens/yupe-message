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
        '/inbox' => 'message/message/inbox',
        '/outbox' => 'message/message/outbox',
        '/spam' => 'message/message/spam',
        '/compose' => 'message/message/compose',
        '/pm<id:\d+>' => 'message/message/view',
        '/pm<id:\d+>/remove' => 'message/message/delete',
        '/pm<id:\d+>/spam/mark' => 'message/message/spamMark',
        '/pm<id:\d+>/spam/unmark' => 'message/message/spamUnMark',
    ],
];