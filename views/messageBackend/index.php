<?php
/**
 * index.php - backend index view.
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
    Yii::t('MessageModule.message', 'Management'),
);

$this->pageTitle = Yii::t('MessageModule.message', 'Private messages - management');

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
        )
    )
);

$users = CHtml::listData(User::model()->findAll(), 'id', 'nick_name');
?>

    <div class="page-header">
        <h1>
            <?php echo Yii::t('MessageModule.message', 'Private messages'); ?>
            <small><?php echo Yii::t('MessageModule.message', 'management'); ?></small>
        </h1>
    </div>

    <p>
        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="collapse" data-target="#search-toggle">
            <i class="fa fa-search">&nbsp;</i>
            <?php echo Yii::t('MessageModule.message', 'Find messages'); ?>
            <span class="caret">&nbsp;</span>
        </a>
    </p>

    <div id="search-toggle" class="collapse out search-form">
        <?php
        Yii::app()->clientScript->registerScript(
            'search',
            "
    $('.search-form form').submit(function () {
        event.preventDefault();

        $.fn.yiiGridView.update('message-grid', {
            data: $(this).serialize()
        });
    });
"
        );
        $this->renderPartial('_search', array('model' => $model, 'users' => $users));
        ?>
    </div>

<?php
$users = CHtml::listData(User::model()->findAll(), 'id', 'nick_name');
$this->widget(
    'yupe\widgets\CustomGridView',
    array(
        'id'           => 'message-grid',
        'dataProvider' => $model->search(),
        'filter'       => $model,
        'columns'      => array(
            'id',
            array(
                'name'  => 'sender_id',
                'type'  => 'raw',
                'filter' => $users,
                'value' => 'CHtml::link($data->sender->nick_name, array("/user/userBackend/view", "id" => $data->sender_id))',
            ),
            array(
                'name'  => 'recipient_id',
                'type'  => 'raw',
                'filter' => $users,
                'value' => 'CHtml::link($data->recipient->nick_name, array("/user/userBackend/view", "id" => $data->recipient_id))',
            ),
            array(
                'name'  => 'body',
                'type'  => 'raw',
                'value' => 'StringHelper::truncate($data->body, 150);',
            ),
            array(
                'name'  => 'is_read',
                'type'  => 'raw',
                'filter' => $model->getStatusList(),
                'value' => '$data->getStatus();',
            ),
            array(
                'name'   => 'sent_at',
            ),

            array(
                'header'      => Yii::t('UserModule.user', 'Management'),
                'class'       => 'yupe\widgets\CustomButtonColumn',
                'template'    => '{view}{update}{delete}',
            ),
        ),
    )
); ?>