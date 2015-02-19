<?php
/**
 * _inbox.php - inbox item view file.
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
        <?php echo CHtml::link('<i class="fa fa-gavel"></i>', ['/message/message/spamMark', 'id' => $data->id],
            [
                'class' => 'message-preview-action',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => Yii::t('MessageModule.message', 'Is spam')
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
                <?php echo CHtml::link(CHtml::image($data->sender->getAvatar(50, 50), $data->sender->nick_name, [
                    'height' => '50px',
                    'class' => 'img-responsive'
                ]),[
                    '/user/people/userInfo', 'username' => $data->sender->nick_name
                ]); ?>
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
