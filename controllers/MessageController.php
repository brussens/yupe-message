<?php
/**
 * Class MessageController - контроллер фронтальной части модуля.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@nativeweb.ru>
 * @link http://nativeweb.ru
 * @copyright 2014 Native Web.
 * @package yupe.modules.message.controllers
 * @since 0.1α
 *
 */
class MessageController extends yupe\components\controllers\FrontController
{

    /**
     * @var string
     */
    public $layout = '/layouts/message';

    /**
     * @return array
     */
    public function filters()
    {
        return [
            'accessControl',
        ];
    }

    /**
     * @return array
     */
    public function accessRules()
    {
        return [
            ['deny',
                'actions' => [
                    'inbox',
                    'outbox',
                    'compose',
                    'view',
                    'delete',
                    'spam',
                    'spamMark',
                    'spamUnMark',
                ],
                'users' => ['?'],
            ],
            ['allow',
                'actions' => [
                    'inbox',
                    'outbox',
                    'compose',
                    'view',
                    'delete',
                    'spam',
                    'spamMark',
                    'spamUnMark',
                ],
                'users' => ['@'],
            ],
        ];
    }

    /**
     *  Входящие сообщения
     */
    public function actionInbox()
    {
        $this->render('inbox', [
            'dataProvider' => Message::inbox()
        ]);
    }

    /**
     * Исходящие сообщения
     */
    public function actionOutbox()
    {
        $this->render('outbox', [
            'dataProvider' => Message::outbox()
        ]);
    }

    /**
     *  СОздание письма.
     */
    public function actionCompose()
    {
        $model = new ComposeForm;

        if(Yii::app()->request->getPost('ComposeForm')) {

            $model->attributes = Yii::app()->request->getPost('ComposeForm');

            if($model->validate() && $model->send()) {

                Yii::app()->getUser()->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('MessageModule.message', 'Your message has been sent successfully')
                );
                $this->redirect(['/message/message/outbox']);
            }
            else {
                Yii::app()->getUser()->setFlash(
                    yupe\widgets\YFlashMessages::ERROR_MESSAGE,
                    Yii::t('MessageModule.message', 'When you send an error occurred, please try again later')
                );
            }
        }
        $this->render('compose', [
            'model' => $model
        ]);
    }

    /**
     * Просмотр письма
     *
     * @param $id
     * @throws CDbException
     * @throws CHttpException
     */
    public function actionView($id)
    {
        $model = Message::model()->findByPk($id);// Добавить фильтрафию

        if(!$model || !$model->getHasAccess()) {
            throw new CHttpException(404, Yii::t('MessageModule.message', 'Such a message does not exist'));
        }
        $model->markAsRead();
        $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Удаление письма.
     *
     * @param $id
     * @throws CDbException
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        $model = Message::model()->findByPk($id);

        if(!$model || !$model->getHasAccess()) {
            throw new CHttpException(404, Yii::t('MessageModule.message', 'Such a message does not exist'));
        }
        $model->markAsDelete();
        $this->redirect(Yii::app()->request->urlReferrer);
    }

    /**
     *  Спам письма.
     */
    public function actionSpam()
    {
        $this->render('spam', [
            'dataProvider' => Message::spamBox()
        ]);
    }

    /**
     *
     * Пометка письма, как спам.
     *
     * @param $id
     * @throws CDbException
     * @throws CHttpException
     */
    public function actionSpamMark($id)
    {
        $model = Message::model()->findByPk($id);

        if(!$model || !$model->getHasAccess()) {
            throw new CHttpException(404, Yii::t('MessageModule.message', 'Such a message does not exist'));
        }
        $model->markAsSpam();
        $this->redirect(Yii::app()->request->urlReferrer);
    }

    /**
     * Пометка письма, как не спам.
     *
     * @param $id
     * @throws CDbException
     * @throws CHttpException
     */
    public function actionSpamUnMark($id)
    {
        $model = Message::model()->findByPk($id);

        if(!$model || !$model->getHasAccess()) {
            throw new CHttpException(404, Yii::t('MessageModule.message', 'Such a message does not exist'));
        }
        $model->markAsNotSpam();
        $this->redirect(Yii::app()->request->urlReferrer);
    }

}