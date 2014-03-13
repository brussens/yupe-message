<?php
/**
 * Описание: Базовый контроллер модуля Message
 */

class BaseController extends \yupe\components\controllers\FrontController {
    public function init() {
        if (!Yii::app()->user->isAuthenticated()) {
            $this->redirect(array('/user/account/login'));
        }
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('application.modules.message.views.assets.css').'/messageModule.css'
            )
        );
        return parent::init();
    }
    /**
     * Обрезка строк
     */
    public function toCut($str,$len=100,$div=" ") {
        //Обрезка Строки до заданной максимальной длинны, с округлением до посленего символа - разделителя (в меньшую сторону)
        //например  toCut('Мама мыла раму',14," ") вернет "Мама мыла"
        if (strlen($str)<=$len){
            return $str;
        }
        else{
            $str=substr($str,0,$len);
            $pos=strrpos($str,$div);
            $str=substr($str,0,$pos);
            return $str.' ...';
        }
    }
}