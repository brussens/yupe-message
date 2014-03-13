<div class="row">
<div class="span9 offset1 y-mess-item" style="padding:10px 0;">
        <div class="span2">
            <img class="pull-left" style="margin-left:10px;" src="<?php echo $data->sender->getAvatar('30'); ?>" alt="<?php echo $data->sender->nick_name; ?>" />
            <div class="pull-left" style="margin-left:10px;">
                <div><?php echo CHtml::link($data->sender->nick_name, $this->createUrl('/backend/user/user/view', array('id'=>$data->sender_id))); ?></div>
                <div style="font-size:10px;"><?php $this->widget('application.modules.message.widgets.PeopleDate',array('date'=>$data->send_date, 'type' => 1)); ?></div>
            </div>
        </div>
        <div class="span8">
            <div class="pull-left" style="margin-left:10px;">
                <div><?php echo $data->subject; ?></div>
                <div style="font-size:12px;"><?php echo $data->body; ?></div>
            </div>
        </div>
        <div class="span2">
            <?php
            $this->widget(
                'bootstrap.widgets.TbButtonGroup',
                array(
                    'size' => 'small',
                    'buttons' => array(
                        array('url' => array('inbox/remove', 'message_id'=>$model->id), 'icon' => 'remove', 'type' => 'info', 'htmlOptions'=>array('title'=>Yii::t('MessageModule.message', 'Remove'))),
                        array('url' => array('/message/messageBackend/block', 'user_id'=>$data->sender_id), 'icon' => 'legal', 'type' => 'danger', 'htmlOptions'=>array('title'=>Yii::t('MessageModule.message', 'Block spammer'), 'confirm'=>Yii::t('MessageModule.message', 'Are you sure?'))),
                        array('url' => array('inbox/makeSpam', 'message_id'=>$model->id), 'icon' => 'shield', 'type' => 'success', 'htmlOptions'=>array('title'=>Yii::t('MessageModule.message', 'This is not spam'))),
                    )
                )
            );


            ?>
        </div>
</div>
</div>