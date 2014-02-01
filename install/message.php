<?php
/**
 * Файл конфигурации модуля message
 *
 * @author BrusSENS <brusenskiydmitriy@gmail.com>
 * @link http://yupe.ru
 * @copyright 2014 BrusSENS
 * @package yupe.modules.message
 * @since 0.6-beta
 *
 */
return array(
    'module'   => array(
        'class'  => 'application.modules.message.MessageModule',
    ),
    'import'    => array(
        'application.modules.message.models.*',
    ),
    'component' => array(),

    'rules'     => array(
    '/messages'=> 'message/message/inbox',
    '/messages/outbox'=> 'message/message/outbox',
    '/message/create'=> 'message/message/create',
    '/message/view/<message_id>'=> 'message/message/view',
    ),
);