<?php
/**
 * Class MessageAssets - register module assets.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message.assets
 * @since 0.2.0α
 *
 */

class MessageAssets
{
    public $assetPath;

    const PACKAGE_ID = 'Message-module';

    public $css = [
        'css/message.css'
    ];

    public $js = [
        'js/moment/moment.min.js',
        'js/timeago.js'
    ];

    public $depends = [
        'jquery'
    ];

    public function publish()
    {
        $this->js[] = 'js/moment/locale/' . Yii::app()->language. '.js';

        $package = [
            'baseUrl' => $this->getAssetUrl(),
            'js' => $this->js,
            'css' => $this->css,
            'depends' => $this->depends
        ];

        return Yii::app()->clientScript->addPackage(self::PACKAGE_ID, $package)
            ->registerPackage(self::PACKAGE_ID)
            ->registerScript(
                $this->getId(),
                'jQuery(".timeago").dateFormat();
                jQuery("[data-action=\"data-href\"]").on("click", function(){
                    window.location=$(this).attr("data-href");
                });',
                CClientScript::POS_READY
            );
    }

    public function getId()
    {
        return self::PACKAGE_ID.'_'.uniqid();
    }

    public function getAssetUrl()
    {
        return Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('message.assets.web'));
    }
}