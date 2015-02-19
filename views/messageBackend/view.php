<?php
/**
 * view.php - backend view.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@nativeweb.ru>
 * @link http://nativeweb.ru
 * @copyright 2014 Native Web.
 * @package yupe.modules.message.views.messageBackend
 * @since 0.2.0α
 *
 */
$this->breadcrumbs = array(
    Yii::t('MessageModule.message', 'Private messages') => array('/message/messageBackend/index'),
    $model->id,
);

$this->pageTitle = Yii::t('MessageModule.message', 'Private messages - show');

$this->menu = array(
    array(
        'label' => Yii::t('MessageModule.message', 'Private messages'),
        'items' => array(
            array(
                'icon'  => 'fa fa-fw fa-list-alt',
                'label' => Yii::t('MessageModule.message', 'Manage messages'),
                'url'   => array('/message/messageBackend/index')
            ),
            array(
                'icon'  => 'fa fa-fw fa-plus-square',
                'label' => Yii::t('MessageModule.message', 'Create message'),
                'url'   => array('/message/messageBackend/create')
            ),
            array('label' => Yii::t('MessageModule.message', 'Message') . ' «' . $model->id . '»'),
            array(
                'icon'  => 'fa fa-fw fa-pencil',
                'label' => Yii::t('MessageModule.message', 'Edit message'),
                'url'   => array(
                    '/message/messageBackend/update',
                    'id' => $model->id
                )
            ),
            array(
                'icon'        => 'fa fa-fw fa-trash-o',
                'label'       => Yii::t('MessageModule.message', 'Remove message'),
                'url'         => '#',
                'linkOptions' => array(
                    'submit'  => array('/message/messageBackend/delete', 'id' => $model->id),
                    'params'  => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken),
                    'confirm' => Yii::t('MessageModule.message', 'Do you really want to remove this message?')
                ),
            ),
        )
    )
);
?>
    <div class="page-header">
        <h1>
            <?php echo Yii::t('MessageModule.message', 'Show message'); ?> №<?php echo $model->id; ?>
        </h1>
    </div>

<?php $this->widget(
    'bootstrap.widgets.TbDetailView',
    array(
        'data' => $model,
        'attributes' => array(
            'id',
            array(
                'name'  => 'is_read',
                'value' => $model->getStatus(),
            ),
            array(
                'name'  => 'sender_id',
                'value' => $model->sender->nick_name,
            ),
            array(
                'name'  => 'recipient_id',
                'value' => $model->recipient->nick_name,
            ),
            'body',
        ),
    )
); ?>