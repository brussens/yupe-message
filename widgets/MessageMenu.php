<?php
/**
 * Создано Hoswac ltd.
 * Пользователь: BrusSENS
 * Дата: 31.01.14
 * Время: 16:31
 * Описание: Виджет меню фронтэнда.
 */
Yii::import('bootstrap.widgets.TbMenu');
class MessageMenu extends TbMenu {
    public function init() {
        $this->type='list';
        $this->type='list';
        $this->encodeLabel=false;
        $this->items=$this->items();
        parent::init();
    }
    public function items() {
        $items[]=array('label' => Yii::t('MessageModule.message', 'Inbox') . ((Message::model()->inboxcount!=0) ? '<span class="badge pull-right">' . Message::model()->inboxcount . '</span>':''),'url' => array('inbox/inbox'),'active' => (Yii::app()->getController()->getId() == 'inbox') ? true : null);
        $items[]=array('label' => Yii::t('MessageModule.message', 'Submitted') . ((Message::model()->outboxcount!=0) ? '<span class="badge pull-right">' . Message::model()->outboxcount . '</span>':''),'url' => array('outbox/outbox'),'active' => (Yii::app()->getController()->getId() == 'outbox') ? true : null);
        return $items;
    }
}