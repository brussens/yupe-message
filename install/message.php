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
        'application.modules.message.components.*',
    ),
    'component' => array(),

    'rules'     => array(
        '/messages/inbox'=> 'message/inbox/inbox',
        '/messages/outbox'=> 'message/outbox/outbox',
        '/message/create'=> 'message/message/create',
        '/message/inbox/message<message_id>'=> 'message/inbox/view',
        '/message/outbox/message<message_id>'=> 'message/outbox/view',
        '/message/outbox/remove/message<message_id>'=> 'message/outbox/remove',
        '/message/inbox/remove/message<message_id>'=> 'message/inbox/remove',
        '/message/itspam/message<message_id>'=> 'message/inbox/makeSpam',
    ),
);