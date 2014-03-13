<div class="span7 y-mess-item <?php if (!$data->isRead) echo 'y-mess-no-read'; ?>" style="padding:10px 0;">
    <div class="row">
        <div class="span2">
            <img class="pull-left" style="margin-left:10px;" src="<?php echo $data->recipient->getAvatar('30'); ?>" alt="<?php echo $data->sender->nick_name; ?>" />
            <div class="pull-left" style="margin-left:10px;">
                <div><?php echo CHtml::link($data->recipient->nick_name, array('/user/people/userInfo', 'username'=>$data->recipient->nick_name)); ?></div>
                <div style="font-size:10px;"><?php $this->widget('application.modules.message.widgets.PeopleDate',array('date'=>$data->send_date, 'type' => 1)); ?></div>
            </div>
        </div>
        <div class="span4">
            <div class="pull-left" style="margin-left:10px;">
                <div><?php echo CHtml::link($this->toCut((!empty($data->reply_to)) ? 'Re:'.$data->subject : $data->subject), array('outbox/view', 'message_id'=>$data->id)); ?></div>
                <div style="font-size:12px;"><?php echo $this->toCut($data->body); ?></div>
            </div>
        </div>
        <div class="span1">
            <?php $this->widget(
                'bootstrap.widgets.TbButton',
                array('url' => array('outbox/remove', 'message_id'=>$data->id), 'size' => 'mini', 'icon' => 'remove', 'type' => 'info', 'htmlOptions'=>array('title'=>Yii::t('MessageModule.message', 'Remove'), 'confirm'=>Yii::t('MessageModule.message', 'Are you sure?'), 'class'=>'button-rem'))
            );?>
        </div>
    </div>
</div>