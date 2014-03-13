<?php $this->breadcrumbs=array(
Yii::t('MessageModule.message', 'Messages')=>$this->createUrl('inbox/inbox'),
Yii::t('MessageModule.message', 'Create message')
);
?>
<div class="row">
    <div class="span2">
        <?php $this->widget(
            'application.modules.message.widgets.MessageMenu'
        );?>
    </div>
    <div class="span7">
        <div class="form">
            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id'=>'message-CreateMessage-form',
                'enableAjaxValidation'=>true,
                'htmlOptions'=>array('class'=>'well'),
            )); ?>

            <p class="note"><?php echo Yii::t('MessageModule.message','Fields with <span class="required">*</span> are required');?></p>

            <?php echo $form->errorSummary($model); ?>
            <?php echo $form->textFieldRow($model, 'nick_name', array('class'=>'span3')); ?>
            <?php echo $form->textFieldRow($model, 'subject', array('class'=>'span3')); ?>

            <?php echo $form->textAreaRow($model, 'body', array('class'=>'span6', 'rows'=>9)); ?>

            <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>Yii::t('MessageModule.message','Send'))); ?>
            <?php $this->widget(
                'bootstrap.widgets.TbButton',
                array(
                    'label' => Yii::t('MessageModule.message', 'Cancel'),
                    'type' => 'danger',
                    'url'=>array('message/inbox'),
                )
            ); ?>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>