<?php
/**
 * Class Message - Message module mail class.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message
 * @since 0.1α
 *
 */

class MessageModule extends \yupe\components\WebModule
{
    const VERSION = '0.2.0α';

    public function getDependencies()
    {
        return [
            'user',
            'notify'
        ];
    }

    public function getAdminPageLink()
    {
        return '/message/messageBackend/index';
    }

    public function getNavigation()
    {
        return [
            [
                'icon'  => 'fa fa-fw fa-list-alt',
                'label' => Yii::t('MessageModule.message', 'Messages list'),
                'url'   => ['/message/messageBackend/index']
            ],
            [
                'icon'  => 'fa fa-fw fa-plus-square',
                'label' => Yii::t('MessageModule.message', 'Create message'),
                'url'   => ['/message/messageBackend/create']
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
        $this->setImport([
                'message.models.*',
                'message.components.*',
                'message.forms.*',
        ]);

        parent::init();
    }
}
