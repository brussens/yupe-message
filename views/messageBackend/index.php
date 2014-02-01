<?php
    $this->breadcrumbs = array(
        Yii::t('MessageModule.message', 'Приватные сообщения') => array('/message/messageBackend/index'),
        Yii::t('MessageModule.message', 'Управление'),
    );

    $this->pageTitle = Yii::t('MessageModule.message', 'Приватные сообщения - Управление');

    $this->menu = array(
        array('label' => Yii::t('MessageModule.message', 'Спам'), 'items' => array(
            array('icon' => 'list-alt', 'label' => Yii::t('MessageModule.message', 'Список спама'), 'url' => array('/message/spamBackend/index')),
        )),
    );
?>