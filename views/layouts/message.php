<?php
/**
 * message.php - лайаут модуля.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@nativeweb.ru>
 * @link http://nativeweb.ru
 * @copyright 2014 Native Web.
 * @package yupe.modules.message.views.layouts
 * @since 0.2.0α
 *
 */
?>

<?php
// Регистрация ресурсов.
Yii::import('message.assets.MessageAssets');
(new MessageAssets())->publish();


// Scan new messages
$inboxCounter = false;
$outboxCounter = false;
$spamCounter = false;
$inbox = Message::model()->countByAttributes([
    'recipient_id' => Yii::app()->getUser()->getId(),
    'is_read' => Message::STATUS_NEW,
    'recipient_del' => Message::NOT_DELETED
]);
$outbox = Message::model()->countByAttributes([
    'sender_id' => Yii::app()->getUser()->getId(),
    'is_read' => Message::STATUS_NEW,
    'sender_del' => Message::NOT_DELETED
]);
$spam = Message::model()->countByAttributes([
    'recipient_id' => Yii::app()->getUser()->getId(),
    'recipient_del' => Message::NOT_DELETED,
    'is_spam' => Message::SPAM,
]);
if($inbox) {
    $inboxCounter = '<span class="label pull-right">'.$inbox.'</span>';
}
if($outbox) {
    $outboxCounter = '<span class="label pull-right">'.$outbox.'</span>';
}
if($spam) {
    $spamCounter = '<span class="label pull-right">'.$spam.'</span>';
}
?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="row">
    <div class="col-sm-3">
        <?php echo CHtml::link(Yii::t('MessageModule.message', 'Compose'), ['/message/message/compose'], ['class' => 'btn btn-danger btn-block']); ?>
        <hr>
        <?php $this->widget('zii.widgets.CMenu', [
            'id' => 'message-nav',
            'encodeLabel' => false,
            'htmlOptions' => [
                'class' => 'nav nav-pills nav-stacked'
            ],
            'items' => [
                [
                    'label' => Yii::t('MessageModule.message', 'Inbox').$inboxCounter,
                    'url' => ['/message/message/inbox'],
                ],
                [
                    'label' => Yii::t('MessageModule.message', 'Outbox').$outboxCounter,
                    'url' => ['/message/message/outbox'],
                ],
                [
                    'label' => Yii::t('MessageModule.message', 'Spam').$spamCounter,
                    'url' => ['/message/message/spam'],
                ],
            ]
        ]); ?>
    </div>
    <div class="col-sm-9 message-wrapper">
        <?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>