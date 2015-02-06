<?php
/**
 * compose.php - new message form.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message.views.message
 * @since 0.1-α
 *
 */

$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    [
        'id'                     => 'message-form',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'type'                   => 'vertical',
        'htmlOptions'            => ['class' => 'well'],
        'action' => ['/message/message/compose']
    ]
); ?>
<?php if(!$model->recipient): ?>
<div class="form-group">
    <?php $this->widget(
        'booster.widgets.TbSelect2',
        [
            'model' => $model,
            'attribute' => 'recipient',
            'data' => CHtml::listData(User::model()->findAll('id != :id', [':id' => Yii::app()->user->id]), 'id', 'nick_name'),
            'options' => [
                'placeholder' => Yii::t('MessageModule.message', 'Check a recipient'),
                'width' => '100%',
            ],
        ]
    ); ?>
</div>
<?php else: ?>
    <?php echo $form->hiddenField($model,'recipient',['type'=>"hidden"]); ?>
<?php endif; ?>
<?php echo $form->textAreaGroup($model, 'body',[
    'label' => false,
    'widgetOptions' => [
        'htmlOptions' => [
            'placeholder' => Yii::t('MessageModule.message', 'Enter your message text')
        ],
    ]
]); ?>

<?php $this->widget(
    'bootstrap.widgets.TbButton',
    [
        'buttonType'  => 'submit',
        'htmlOptions' => ['name' => 'submit-type', 'value' => 'index'],
        'label'       => Yii::t('MessageModule.message', 'Send'),
    ]
); ?>

<?php $this->endWidget(); ?>