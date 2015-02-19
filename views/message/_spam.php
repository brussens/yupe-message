<?php
/**
 * _spam.php - spam item view file.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@nativeweb.ru>
 * @link http://nativeweb.ru
 * @copyright 2014 Native Web.
 * @package yupe.modules.message.views.message
 * @since 0.1α
 *
 */
?>
<div data-href="<?php echo Yii::app()->createUrl('/message/message/view', ['id' => $data->id]); ?>" data-action="data-href" class="<?php echo ($data->getIsNew() ? 'hot ' : ''); ?>clearfix message-preview-item">
    <div class="message-preview-actions">
        <?php echo CHtml::link('<i class="fa fa-life-ring"></i>', ['/message/message/spamUnMark', 'id' => $data->id],
            [
                'class' => 'message-preview-action',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => Yii::t('MessageModule.message', 'Is not spam')
            ]
        ); ?>
        <?php echo CHtml::link('<i class="fa fa-times"></i>', ['/message/message/delete', 'id' => $data->id],
            [
                'class' => 'message-preview-action',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => Yii::t('MessageModule.message', 'Delete')
            ]
        ); ?>
    </div>
    <table>
        <tr>
            <td>
                <a href="<?php echo Yii::app()->createUrl('/user/people/userInfo', ['username' => $data->sender->nick_name]) ?>">
                    <img class="img-responsive" src="<?php echo $data->sender->getAvatar('50'); ?>" alt="<?php echo $data->sender->nick_name; ?>" />
                </a>
            </td>
            <td class="message-preview-info">
                <div>
                    <?php echo CHtml::link($data->sender->nick_name, ['/user/people/userInfo', 'username' => $data->sender->nick_name]); ?>
                </div>
                <div class="message-preview-info-time"><time class="timeago" datetime="<?php echo $data->sent_at; ?>"><?php echo $data->sent_at; ?></time></div>
            </td>
            <td>
                <div class="message-preview-body"><?php echo StringHelper::truncate($data->body, 150); ?></div>
            </td>
        </tr>
    </table>
</div>
