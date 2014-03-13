<?php
/**
 * MessageModule основной класс модуля message
 *
 * @author BrusSENS <brusenskiydmitriy@gmail.com>
 * @link http://yupe.ru
 * @copyright 2014 BrusSENS
 * @package yupe.modules.message
 * @since 0.6-beta
 *
 */


use yupe\components\WebModule;

class MessageModule extends WebModule
{
    public $messagePerPage = 20;
    // название модуля
    public function getName()
    {
        return Yii::t('MessageModule.client', 'Приватные сообщения');
    }

    // описание модуля
    public function getDescription()
    {
        return Yii::t('MessageModule.client', 'Модуль для организации приватных сообщений между пользователями');
    }

    // автор модуля
    public function getAuthor()
    {
        return Yii::t('MessageModule.client', 'Дмитрий Брусенский (BrusSENS)');
    }

    // контактный email автора
    public function getAuthorEmail()
    {
        return Yii::t('MessageModule.client', 'brussens@hoswac.com');
    }

    // сайт автора или страничка модуля
    public function getUrl()
    {
        return Yii::t('MessageModule.client', 'http://hoswac.com');
    }

    public function getCategory()
    {
        return Yii::t('MessageModule.client', 'Сервисы');
    }

    public function getIsInstallDefault()
    {
        return false;
    }

    public function getIsNoDisable()
    {
        return false;
    }

    public function getVersion()
    {
        return Yii::t('MessageModule.user', '0.1beta-1');
    }

    public function getIcon()
    {
        return 'envelope';
    }

    public function getDependencies()
    {
        return array('user');
    }

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'message.models.*',
			'message.components.*',
            'message.forms.*',
		));
	}

    public function getAdminPageLink()
    {
        return '/message/messageBackend/spam';
    }

    public function getNavigation()
    {
        return array(
            array('label' => Yii::t('MessageModule.message', 'Spam')),
            array('icon' => 'list-alt', 'label' => Yii::t('MessageModule.message', 'Spam list'), 'url' => array('/message/messageBackend/spam')),
        );
    }

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
