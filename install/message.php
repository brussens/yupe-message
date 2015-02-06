<?php
/**
 * Module configuration file.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message.install
 * @since 0.1-α
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
        '/compose' => 'message/message/compose',
        '/pm<mid:\d+>' => 'message/message/view',
    ],
];