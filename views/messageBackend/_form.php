<?php
$users = CHtml::listData(User::model()->findAll(), 'id', 'nick_name');
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    [
        'id'                     => 'message-form',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'type'                   => 'vertical',
        'htmlOptions'            => ['class' => 'well'],
    ]
); ?>

    <div class="alert alert-info">
        <?php echo Yii::t('MessageModule.message', 'Fields with'); ?>
        <span class="required">*</span>
        <?php echo Yii::t('MessageModule.message', 'are required'); ?>
    </div>

<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
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
        <div class="col-sm-6">
            <div class="form-group">
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
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
            <?php echo $form->dateTimePickerGroup(
                $model,
                'sent_at',
                array(
                    'widgetOptions' => array(
                        'options' => array(
                            'format'      => 'yy-mm-dd hh:ii:ss',
                            'todayBtn' => true,
                            'weekStart'   => 1,
                            'autoclose'   => true,
                            'orientation' => 'auto right',
                            'startView'   => 2,
                        ),
                    ),
                    'prepend'       => '<i class="fa fa-calendar"></i>',
                )
            );
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php echo $form->labelEx($model, 'body'); ?>
            <?php
            $this->widget(
                $this->module->getVisualEditor(),
                [
                    'model'     => $model,
                    'attribute' => 'body',
                ]
            ); ?>
        </div>
    </div>

<?php $this->widget(
    'bootstrap.widgets.TbButton',
    [
        'buttonType' => 'submit',
        'context'    => 'primary',
        'label'      => $model->isNewRecord ? Yii::t('MessageModule.message', 'Create message and continue') : Yii::t(
            'MessageModule.message',
            'Save message and continue'
        ),
    ]
); ?>

<?php $this->widget(
    'bootstrap.widgets.TbButton',
    [
        'buttonType'  => 'submit',
        'htmlOptions' => ['name' => 'submit-type', 'value' => 'index'],
        'label'       => $model->isNewRecord ? Yii::t('MessageModule.message', 'Create message and close') : Yii::t(
            'MessageModule.message',
            'Save message and close'
        ),
    ]
); ?>

<?php $this->endWidget(); ?>