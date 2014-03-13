<div class="span11">
    <hr>
            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id'=>'message-reply-form',
                'enableAjaxValidation'=>true,
            )); ?>

            <p class="note"><?php echo Yii::t('MessageModule.message','Fields with <span class="required">*</span> are required');?></p>

            <?php echo $form->errorSummary($model); ?>
            <?php echo $form->textAreaRow($model, 'body', array('class'=>'span12', 'rows'=>9)); ?>

            <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>Yii::t('MessageModule.message','Send'))); ?>
            <?php $this->endWidget(); ?>
</div>