<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
    Yii::t('MessageModule.message', 'Inbox')=>$this->createUrl('inbox/inbox'),
    $model->subject
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
        <hr class="y-mess">
        <?php $this->widget(
            'application.modules.message.widgets.MessageMenu'
        );?>
    </div>
    <div class="well span6">
        <div class="row-fluid">
            <div class="span2" >
                <img src="<?php echo $model->sender->getAvatar('140'); ?>" alt="<?php echo $model->sender->nick_name; ?>" class="img-circle">
            </div>

            <div class="span8">
                <h3 class="y-mess-nickname-link"><?php echo CHtml::link($model->sender->nick_name, array('/user/people/userInfo', 'username'=> $model->sender->nick_name)); ?></h3>
                <p><b><?php echo Yii::t('MessageModule.message', 'Dispatch date'); ?>:</b> <?php $this->widget('application.modules.message.widgets.PeopleDate',array('date'=>$model->send_date, 'type' => 1)); ?></p>
                <p><b><?php echo Yii::t('MessageModule.message', 'Subject'); ?>:</b> <?php echo (!empty($model->reply_to)) ? 'Re:'.$model->subject : $model->subject ?></p>
            </div>

            <div class="span2">
                <?php
                $this->widget(
                    'bootstrap.widgets.TbButtonGroup',
                    array(
                        'size' => 'mini',
                        'buttons' => array(
                            array('url' => array('inbox/remove', 'message_id'=>$model->id), 'icon' => 'remove', 'type' => 'info', 'htmlOptions'=>array('title'=>Yii::t('MessageModule.message', 'Remove'), 'confirm'=>Yii::t('MessageModule.message', 'Are you sure?'))),
                            array('url' => array('inbox/makeSpam', 'message_id'=>$model->id), 'icon' => 'warning-sign', 'type' => 'danger', 'htmlOptions'=>array('title'=>Yii::t('MessageModule.message', 'This is spam!'), 'confirm'=>Yii::t('MessageModule.message', 'Are you sure?'))),
                        )
                    )
                );


                ?>
            </div>
            <div class="span11 row">
                <hr>
                <p><?php echo $model->body; ?></p>
            </div>
            <?php $this->renderPartial('/message/reply',array(
                'model'=>$reply,
            )); ?>
        </div>
    </div>
</div>