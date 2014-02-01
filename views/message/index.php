<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
    Yii::t('MessageModule.message', 'Inbox'),
);
?>
<div class="row">
    <div class="span2">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => Yii::t('MessageModule.message', 'Write'),
                'type' => 'success',
                'block'=>true,
                'url'=>array('message/create'),
            )
        ); ?>
        <hr />
        <?php $this->widget(
            'application.modules.message.widgets.MessageMenu'
        );?>
    </div>
    <div class="span7">
            <?php $this->widget(
                'bootstrap.widgets.TbThumbnails',
                array(
                'dataProvider' => $dataProvider,
                'template' => "{items} {pager}",
                'itemView' => '_view',
                )
            );?>
    </div>
</div>