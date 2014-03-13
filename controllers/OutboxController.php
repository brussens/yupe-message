<?php
/**
 * Class OutboxController
 *
 * Контроллер для работы с отправленными сообщениями.
 */
class OutboxController extends BaseController {
    /**
     * Экшн для показа отправленных сообщений
     */
    public function actionOutbox() {
        $dataProvider= Message::model()->outbox;
        $this->render('index', array('dataProvider'=>$dataProvider));
    }
    /**
     * Экшн просмотра отправленного сообщения
     */
    public function actionView() {
        $model = Message::model()->messageView($_GET['message_id']);
        if (!$model) {
            throw new CHttpException(404, Yii::t('MessageModule.message', 'This message does not exist'));
        }
        $reply=$this->reply($model);
        $this->render('view',array('model'=>$model, 'reply'=>$reply));
    }
    /**
     * Экшн удаления отправленного сообщения
     */
    public function actionRemove() {
        if(!$_GET['message_id']) {
            throw new CHttpException(404, Yii::t('MessageModule.message', 'This message does not exist'));
        }
        else {
            $result=Message::model()->removeOutbox($_GET['message_id']);
            if($result) {
                Yii::app()->user->setFlash(
                    YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('MessageModule.message', 'Message has been successfully removed')
                );
            }
            else {
                Yii::app()->user->setFlash(
                    YFlashMessages::ERROR_MESSAGE,
                    Yii::t('MessageModule.message', 'When you remove the error occurred. Perhaps you have no right to commit this action')
                );
            }
            $this->redirect($this->createUrl('outbox/outbox'));
        }
    }
    /**
     * Метод для создания ответа
     */
    public function reply($model) {
        $reply=new ReplyForm;
        if(isset($_POST['ajax']) && $_POST['ajax']==='message-reply-form') {
            echo CActiveForm::validate($reply);
            Yii::app()->end();
        }
        if(isset($_POST['ReplyForm'])) {
            $reply->attributes=$_POST['ReplyForm'];
            $reply->subject=$model->subject;
            $reply->reply_to=$model->id;
            $reply->recipient_id=$model->recipient_id;
            if($reply->validate())
            {
                if($reply->save()) {
                    Yii::app()->user->setFlash(
                        YFlashMessages::SUCCESS_MESSAGE,
                        Yii::t('MessageModule.message', 'Message sent successfully!')
                    );
                    $this->refresh();
                }
            }
        }
        return $reply;
    }
}