<style>
    .y-mess-list tr:hover {
        cursor:pointer;
    }
    .y-mess-list tr td.y-mess-item-check input[type="checkbox"] {
        margin-top:2px;
        display:block;
    }
    .y-mess-list tr td.y-mess-item-check {
        vertical-align: middle;
    }
    .y-mess-no-read {
        background:#e2e7ed;
    }
    .y-mess-no-read:hover {
        background:#e2e7ed !important;
    }
    .y-mess-view-main {
        background:#efefef;
    }
</style>
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
        <table class="y-mess-view-main span7">
            <tr>
                <td><img src="<?php echo $model->sender->getAvatar('100'); ?>" alt="<?php echo $model->sender->nick_name; ?>" /></td>
                <td><?php echo $model->send_date; ?></td>
            </tr>
            <tr>
                <td><?php echo $model->sender->nick_name; ?></td>
                <td><?php echo $model->subject; ?></td>
            </tr>
            <tr>
                <td colspan="2"><?php echo $model->body; ?></td>
            </tr>
        </table>
    </div>
</div>