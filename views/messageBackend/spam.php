<?php
    $this->breadcrumbs = array(
        Yii::t('MessageModule.message', 'Private messages') => array('/message/messageBackend/spam'),
        Yii::t('MessageModule.message', 'Spam management'),
    );

    $this->pageTitle = Yii::t('MessageModule.message', 'Private messages') . ' - ' . Yii::t('MessageModule.message', 'Spam management');

    $this->menu = array(
        array('label' => Yii::t('MessageModule.message', 'Спам'), 'items' => array(
            array('icon' => 'list-alt', 'label' => Yii::t('MessageModule.message', 'Список спама'), 'url' => array('/message/messageBackend/spam')),
        )),
    );
?>

        <?php $this->widget(
            'bootstrap.widgets.TbThumbnails',
            array(
                'dataProvider' => $dataProvider,
                'template' => "{items} {pager}",
                'itemView' => '_spamView',
            )
        );?>
