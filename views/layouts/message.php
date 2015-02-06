<?php
/**
 * message.php - the module layout file.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message.views.layouts
 * @since 0.2-α
 *
 */
?>

<?php
// Registered Assets
Yii::import('message.assets.MessageAssets');
(new MessageAssets())->publish();


// Scan new messages
$inboxCounter = false;
$outboxCounter = false;
$inbox = Message::model()->countByAttributes([
    'recipient_id' => Yii::app()->getUser()->getId(),
    'is_read' => Message::STATUS_NEW
]);
$outbox = Message::model()->countByAttributes([
    'sender_id' => Yii::app()->getUser()->getId(),
    'is_read' => Message::STATUS_NEW
]);
if($inbox) {
    $inboxCounter = '<span class="label pull-right">'.$inbox.'</span>';
}
if($outbox) {
    $outboxCounter = '<span class="label pull-right">'.$outbox.'</span>';
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
            ]
        ]); ?>
    </div>
    <div class="col-sm-9 message-wrapper">
        <?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>