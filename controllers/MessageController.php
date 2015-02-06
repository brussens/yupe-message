<?php
/**
 * Class MessageController - message manipulation controller.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message.controllers
 * @since 0.1-α
 *
 */
class MessageController extends yupe\components\controllers\FrontController
{

    public $layout = '/layouts/message';

    public function actionInbox()
    {

        $dataProvider=new CActiveDataProvider('Message', [
            'criteria' => [
                'condition' => 'recipient_id = :user_id',
                'params' => [
                    ':user_id' => Yii::app()->getUser()->getId()
                ],
                'order' => 'sent_at DESC',
                'with' => ['sender'],
            ],

            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->render('inbox', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionOutbox()
    {

        $dataProvider=new CActiveDataProvider('Message', [
            'criteria' => [
                'condition' => 'sender_id = :user_id',
                'params' => [
                    ':user_id' => Yii::app()->getUser()->getId()
                ],
                'order' => 'sent_at DESC',
                'with' => ['recipient'],
            ],

            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->render('outbox', [
            'dataProvider' => $dataProvider
        ]);
    }

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
                $this->redirect(['/message/message/inbox']);
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

    public function actionView($mid)
    {
        $model = Message::model()->findByPk($mid);

        if(!$model) {
            throw new CHttpException(404, Yii::t('MessageModule.message', 'Such a message does not exist'));
        }

        if($model->getIsNew() && $model->getIsInbox()) {
            $model->saveCounters(['is_read' => Message::STATUS_READ]);
        }

        $this->render('view', [
            'model' => $model
        ]);
    }

    // TODO
    public function actionRemove($mid)
    {

    }

}