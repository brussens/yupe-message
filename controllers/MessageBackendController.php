<?php
/**
 * Class MessageBackendController - backend message manipulation controller.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@nativeweb.ru>
 * @link http://nativeweb.ru
 * @copyright 2014 Native Web.
 * @package yupe.modules.message.controllers
 * @since 0.2α
 *
 */

class MessageBackendController extends \yupe\components\controllers\BackController
{
    public function accessRules()
    {
        return [
            ['allow', 'roles' => ['admin']],
            ['allow', 'actions' => ['create'], 'roles' => ['Message.MessageBackend.Create']],
            ['allow', 'actions' => ['delete'], 'roles' => ['Message.MessageBackend.Delete']],
            ['allow', 'actions' => ['index'], 'roles' => ['Message.MessageBackend.Index']],
            ['allow', 'actions' => ['inlineEdit'], 'roles' => ['Message.MessageBackend.Update']],
            ['allow', 'actions' => ['update'], 'roles' => ['Message.MessageBackend.Update']],
            ['allow', 'actions' => ['view'], 'roles' => ['Message.MessageBackend.View']],
            ['deny']
        ];
    }

    public function actions()
    {
        return [
            'inline' => [
                'class'           => 'yupe\components\actions\YInLineEditAction',
                'model'           => 'Message',
                'validAttributes' => ['is_read']
            ]
        ];
    }

    public function actionIndex()
    {
        $model = new Message('search');
        $model->unsetAttributes(); // clear any default values

        $model->setAttributes(
            Yii::app()->getRequest()->getParam(
                'Message',
                []
            )
        );

        $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionView($id)
    {
        $model = Message::model()->findByPk($id)->with(['sender', 'recipient']);
        $this->render('view', ['model' => $model]);
    }

    public function actionCreate()
    {
        $model = new Message;

        if (($data = Yii::app()->getRequest()->getPost('Message')) !== null) {

            $model->setAttributes($data);

            $model->setAttributes(
                [
                    'is_read' => Message::STATUS_NEW,
                    'sender_del' => 1,
                    'recipient_del' => 1
                ]
            );

            if ($model->save()) {

                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('MessageModule.message', 'Message has been sent')
                );

                $this->redirect(
                    (array)Yii::app()->getRequest()->getPost(
                        'submit-type',
                        ['create']
                    )
                );
            }
        }

        $this->render('create',[
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = Message::model()->findByPk($id);

        if(!$model) {
            throw new CHttpException(404, Yii::t('MessageModule.message', 'Such a message does not exist'));
        }

        if (($data = Yii::app()->getRequest()->getPost('Message')) !== null) {

            $model->setAttributes($data);

            $model->setAttributes(
                [
                    'is_read' => Message::STATUS_NEW,
                    'sender_del' => 1,
                    'recipient_del' => 1
                ]
            );

            if ($model->save()) {

                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('MessageModule.message', 'Message has been changed')
                );

                $this->redirect(
                    (array)Yii::app()->getRequest()->getPost(
                        'submit-type',
                        ['update']
                    )
                );
            }
        }

        $this->render('update',[
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        if (Yii::app()->getRequest()->getIsPostRequest()) {

            // we only allow deletion via POST request
            if (Message::model()->findByPk($id)->delete()) {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('MessageModule.message', 'Message has been removed')
                );
            } else {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::ERROR_MESSAGE,
                    Yii::t('MessageModule.message', 'You can\'t make this changes')
                );
            }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            Yii::app()->getRequest()->getParam('ajax') !== null || $this->redirect(
                (array)Yii::app()->getRequest()->getPost('returnUrl', 'index')
            );
        } else {
            throw new CHttpException(
                400,
                Yii::t('MessageModule.message', 'Bad request. Please don\'t use similar requests anymore')
            );
        }
    }
}