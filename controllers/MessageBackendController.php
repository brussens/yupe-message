<?php
/**
 * Создано Hoswac ltd.
 * Пользователь: BrusSENS
 * Дата: 26.01.14
 * Время: 4:49
 * Описание: Контроллер Администраторского раздела модуля Message;
 */

class MessageBackendController extends yupe\components\controllers\BackController
{
    public $defaultAction = 'Spam';

    public function init() {
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('application.modules.message.views.assets.css').'/messageModule.css'
            )
        );
        return parent::init();
    }

    /**
     * Экшен списка сообщений, помеченных, как спам.
     */
    public function actionSpam() {
        $dataProvider= Message::model()->spamAdm;
        $this->render('spam', array('dataProvider'=>$dataProvider));
    }

    /**
     * Экшен блокировки спамера.
     */
    public function actionBlock() {
        if(isset($_GET['user_id'])) {
            $user=User::model()->findByPk($_GET['user_id']);
            $user->status=$user::STATUS_BLOCK;
            if($user->update()) {
                Yii::app()->user->setFlash(
                    YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('MessageModule.message', 'Пользователь успешно заблокирован.')
                );
            }
            else {
                Yii::app()->user->setFlash(
                    YFlashMessages::ERROR_MESSAGE,
                    Yii::t('MessageModule.message', 'Произошла ошибка.')
                );
            }
        }
        else {
            Yii::app()->user->setFlash(
                YFlashMessages::ERROR_MESSAGE,
                Yii::t('MessageModule.message', 'Некого блокировать :(.')
            );
        }
        $this->redirect($_SERVER['HTTP_REFERER']);
    }
}