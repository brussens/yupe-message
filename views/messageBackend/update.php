<?php
/**
 * update.php - backend update view.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message.views.messageBackend
 * @since 0.2.0α
 *
 */
$this->pageTitle = Yii::t('MessageModule.message', 'Private messages - edit message').' #'.$model->id;
$this->breadcrumbs = array(
    Yii::t('MessageModule.message', 'Private messages') => array('/message/messageBackend/index'),
    Yii::t('MessageModule.message', 'Edit').' #'.$model->id,
);
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
        <?php echo Yii::t('MessageModule.message', 'Private messages'); ?>
        <small><?php echo Yii::t('MessageModule.message', 'Edit').' #'.$model->id; ?></small>
    </h1>
</div>
<?php $this->renderPartial('_form', ['model' => $model]); ?>