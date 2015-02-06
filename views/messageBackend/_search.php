<?php
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'action'      => Yii::app()->createUrl($this->route),
        'method'      => 'get',
        'type'        => 'vertical',
        'htmlOptions' => array('class' => 'well'),
    )
); ?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'sender_id'); ?>
            <?php $this->widget(
                'booster.widgets.TbSelect2',
                [
                    'model' => $model,
                    'attribute' => 'sender_id',
                    'data' => $users,
                    'options' => [
                        'placeholder' => Yii::t('MessageModule.message', 'Check a sender'),
                        'width' => '100%',
                    ],
                ]
            ); ?>
        </div>
        <div class="col-sm-6">
            <?php echo $form->labelEx($model, 'recipient_id'); ?>
            <?php $this->widget(
                'booster.widgets.TbSelect2',
                [
                    'model' => $model,
                    'attribute' => 'recipient_id',
                    'data' => $users,
                    'options' => [
                        'placeholder' => Yii::t('MessageModule.message', 'Check a recipient'),
                        'width' => '100%',
                    ],
                ]
            ); ?>
        </div>
    </div>
</div>



<div class="form-actions">
    <?php $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'buttonType' => 'submit',
            'context'    => 'primary',
            'icon'       => 'fa fa-search',
            'label'      => Yii::t('MessageModule.message', 'Find message'),
        )
    ); ?>

    <?php $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'buttonType' => 'reset',
            'context'    => 'danger',
            'icon'       => 'fa fa-times',
            'label'      => Yii::t('MessageModule.message', 'Reset'),
        )
    ); ?>
</div>

<?php $this->endWidget(); ?>
