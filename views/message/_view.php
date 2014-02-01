<div class="span7 y-mess-item <?php if (!$data->isRead) echo 'y-mess-no-read'; ?>" style="padding:10px 0;">
    <div class="row">
        <div class="span2">
            <img class="pull-left" style="margin-left:10px;" src="<?php echo $data->getRelation(Yii::app()->controller->action->id)->getAvatar('30'); ?>" alt="<?php echo $data->getRelation(Yii::app()->controller->action->id)->nick_name; ?>" />
            <div class="pull-left" style="margin-left:10px;">
                <div><?php echo CHtml::link($data->getRelation(Yii::app()->controller->action->id)->nick_name, array('/user/people/userInfo', 'username'=>$data->getRelation(Yii::app()->controller->action->id)->nick_name)); ?></div>
                <div style="font-size:10px;"><?php $this->widget('application.modules.message.widgets.PeopleDate',array('date'=>$data->send_date, 'type' => 1)); ?></div>
            </div>
        </div>
        <div class="span4">
            <div class="pull-left" style="margin-left:10px;">
                <div><?php echo CHtml::link($this->toCut($data->subject), array('message/view', 'message_id'=>$data->id)); ?></div>
                <div style="font-size:12px;"><?php echo $data->body; ?></div>
            </div>
        </div>
        <div class="span1">
            <?php $this->widget(
                'bootstrap.widgets.TbButtonGroup',
                array(
                    'size' => 'mini',
                    'type' => 'primary',
                    'buttons' => array(
                        array(
                            'label' => '',
                            'htmlOptions' => array(
                                'class'=>'y-mess-buttons'
                            ),
                            'icon' => 'cog',
                            'items' => array(
                                array('label' => Yii::t('MessageModule.message', 'Remove'), 'url' => '#', 'icon' => 'remove',),
                                array('label' => Yii::t('MessageModule.message', 'This is spam!'), 'url' => '#', 'icon' => 'warning-sign',),
                            )
                        ),
                    ),
                )
            );?>
        </div>
    </div>
</div>