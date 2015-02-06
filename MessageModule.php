<?php
/**
 * Class Message - Message module mail class.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message
 * @since 0.1-α
 *
 */

class MessageModule extends \yupe\components\WebModule
{
    const VERSION = '0.2-RC';

    public function getDependencies()
    {
        return array(
            'user',
            'notify'
        );
    }

    public function getAdminPageLink()
    {
        return '/message/messageBackend/index';
    }

    public function getNavigation()
    {
        return [
            ['label' => Yii::t('MessageModule.message', 'Messages')],
            [
                'icon'  => 'fa fa-fw fa-list-alt',
                'label' => Yii::t('MessageModule.message', 'Manage users'),
                'url'   => array('/message/messageBackend/index')
            ],
        ];
    }

    public function getIsInstallDefault()
    {
        return false;
    }

    public function getIsNoDisable()
    {
        return false;
    }

    public function getName()
    {
        return Yii::t('MessageModule.message', 'Private messages');
    }

    public function getCategory()
    {
        return Yii::t('MessageModule.message', 'Services');
    }

    public function getDescription()
    {
        return Yii::t('MessageModule.message', 'Module for private messages between users');
    }

    public function getAuthor()
    {
        return 'BrusSENS';
    }

    public function getAuthorEmail()
    {
        return 'brussens@hoswac.ru';
    }

    public function getUrl()
    {
        return 'http://hoswac.ru';
    }

    public function getVersion()
    {
        return self::VERSION;
    }

    public function getIcon()
    {
        return 'fa fa-fw fa-envelope';
    }

    public function init()
    {
        $this->setImport(
            array(
                'message.models.*',
                'message.components.*',
                'message.forms.*',
            )
        );

        parent::init();
    }
}
