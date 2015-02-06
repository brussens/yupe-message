<?php
/**
 * _outbox.php - outbox item view file.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message.views.message
 * @since 0.1-α
 *
 */
?>
<div data-href="<?php echo Yii::app()->createUrl('/message/message/view', ['mid' => $data->id]); ?>" data-action="data-href" class="<?php echo ($data->getIsNew() ? 'hot ' : ''); ?>clearfix message-preview-item">
    <a href="#remove" class="message-preview-remove"><i class="fa fa-times"></i></a>
    <table>
        <tr>
            <td>
                <a href="<?php echo Yii::app()->createUrl('/user/people/userInfo', ['username' => $data->recipient->nick_name]) ?>">
                    <img class="img-responsive" src="<?php echo $data->recipient->getAvatar('50'); ?>" alt="<?php echo $data->recipient->nick_name; ?>" />
                </a>
            </td>
            <td class="message-preview-info">
                <div>
                    <?php echo CHtml::link($data->recipient->nick_name, ['/user/people/userInfo', 'username' => $data->recipient->nick_name]); ?>
                </div>
                <div class="message-preview-info-time"><time class="timeago" datetime="<?php echo $data->sent_at; ?>"><?php echo $data->sent_at; ?></time></div>
            </td>
            <td>
                <div class="message-preview-body"><?php echo StringHelper::truncate($data->body, 150); ?></div>
            </td>
        </tr>
    </table>
</div>
