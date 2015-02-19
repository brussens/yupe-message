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

        $dataProvider=new CActiveDataProvider('Message', [
            'criteria' => [
                'condition' => 'recipient_id = :user_id AND recipient_del = :recipient_del AND is_spam = :is_spam',
                'params' => [
                    ':user_id' => Yii::app()->getUser()->getId(),
                    ':recipient_del' => Message::NOT_DELETED,
                    ':is_spam' => Message::NOT_SPAM,
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

    /**
     * Исходящие сообщения
     */
    public function actionOutbox()
    {

        $dataProvider=new CActiveDataProvider('Message', [
            'criteria' => [
                'condition' => 'sender_id = :user_id AND sender_del = :sender_del',
                'params' => [
                    ':user_id' => Yii::app()->getUser()->getId(),
                    ':sender_del' => Message::NOT_DELETED,
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

        if($model->getIsNew() && $model->getIsInbox()) {
            $model->is_read = Message::STATUS_READ;
            $model->update(['is_read']);
        }

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

        if($model->getIsInbox()){
            if($model->recipient_del === Message::DELETED) {
                throw new CHttpException(404, Yii::t('MessageModule.message', 'This message already removed'));
            }
            else {
                $model->recipient_del = Message::DELETED;
            }
        }
        elseif($model->getIsOutbox()) {
            if($model->sender_del === Message::DELETED) {
                throw new CHttpException(404, Yii::t('MessageModule.message', 'This message already removed'));
            }
            else {
                $model->sender_del = Message::DELETED;
            }
        }
        if($model->update()) {
            Yii::app()->getUser()->setFlash(
                yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                Yii::t('MessageModule.message', 'Message has been removed successfully')
            );
        }
        else {
            Yii::app()->getUser()->setFlash(
                yupe\widgets\YFlashMessages::ERROR_MESSAGE,
                Yii::t('MessageModule.message', 'When you remove an error occurred, please try again later')
            );
        }
        $this->redirect(Yii::app()->request->urlReferrer);
    }

    /**
     *  Спам письма.
     */
    public function actionSpam()
    {
        $dataProvider=new CActiveDataProvider('Message', [
            'criteria' => [
                'condition' => 'recipient_id = :user_id AND recipient_del = :recipient_del AND is_spam = :is_spam',
                'params' => [
                    ':user_id' => Yii::app()->getUser()->getId(),
                    ':recipient_del' => Message::NOT_DELETED,
                    ':is_spam' => Message::SPAM,
                ],
                'order' => 'sent_at DESC',
                'with' => ['sender'],
            ],

            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->render('spam', [
            'dataProvider' => $dataProvider
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

        if($model->getIsInbox() && !$model->getIsSpam()){
            $model->is_spam = Message::SPAM;
            if($model->update()) {
                Yii::app()->getUser()->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('MessageModule.message', 'Message marked as spam successfully')
                );
            }
            else {
                Yii::app()->getUser()->setFlash(
                    yupe\widgets\YFlashMessages::ERROR_MESSAGE,
                    Yii::t('MessageModule.message', 'When you marked message as spam an error occurred, please try again later')
                );
            }
        }

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

        if($model->getIsInbox() && $model->getIsSpam()){

            $model->is_spam = Message::NOT_SPAM;

            if($model->update()) {
                Yii::app()->getUser()->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('MessageModule.message', 'Message marked as not spam successfully')
                );
            }
            else {
                Yii::app()->getUser()->setFlash(
                    yupe\widgets\YFlashMessages::ERROR_MESSAGE,
                    Yii::t('MessageModule.message', 'When you marked message as not spam an error occurred, please try again later')
                );
            }
        }
        $this->redirect(Yii::app()->request->urlReferrer);
    }

}