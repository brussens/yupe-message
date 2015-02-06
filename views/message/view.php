<?php
/**
 * view.php - message view file.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message.views.message
 * @since 0.1α
 *
 */

if($model->getIsInbox()) {
    $user = $model->sender;
}
else {
    $user = $model->recipient;
}
?>
<div class="row message-view-wrapper">
    <div class="col-sm-2">
        <a href="<?php echo Yii::app()->createUrl('/user/people/userInfo', ['username' => $user->nick_name]) ?>">
            <img class="img-responsive img-thumbnail" src="<?php echo $user->getAvatar('150'); ?>" alt="<?php echo $user->nick_name; ?>" />
        </a>
        <div class="text-center">
            <a href="<?php echo Yii::app()->createUrl('/user/people/userInfo', ['username' => $user->nick_name]) ?>">
                <?php echo $user->nick_name; ?>
            </a>
        </div>
    </div>
    <div class="col-sm-10">
        <?php if(!$model->getIsInbox()): ?>
            <div>
                <a href="<?php echo Yii::app()->createUrl('/user/people/userInfo', ['username' => Yii::app()->user->nick_name]) ?>">
                    <?php echo Yii::t('MessageModule.message', 'You'); ?>
                </a>
                <?php echo Yii::t('MessageModule.message', 'has written'); ?> :
            </div>
        <?php endif; ?>
        <div class="message-view-date">
            <time class="timeago" datetime="<?php echo $model->sent_at; ?>"><?php echo $model->sent_at; ?></time>
        </div>
        <div class="message-view-body">
            <?php echo $model->body; ?>
        </div>
    </div>
</div>
<?php
$this->widget('message.widgets.ComposeWidget', [
    'recipient' => $user->id,
]);
?>
