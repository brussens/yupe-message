<?php
/**
 * Class IntboxController
 *
 * Контроллер для работы с Входящими сообщениями.
 */
class InboxController extends BaseController {
    public function actionInbox() {
        $dataProvider= Message::model()->inbox;
        $this->render('index', array('dataProvider'=>$dataProvider));
    }
    /**
     * Экшн просмотра входящего сообщения
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
     * Экшн удаления входящего сообщения
     */
    public function actionRemove() {
        if(!$_GET['message_id']) {
            throw new CHttpException(404, Yii::t('MessageModule.message', 'This message does not exist'));
        }
        else {
            $result=Message::model()->removeInbox($_GET['message_id']);
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
            $this->redirect($this->createUrl('inbox/inbox'));
        }
    }
    /**
     * Экшн для пометки сообщения получателем, как "спам".
     */
    public function actionMakeSpam() {
        if(!$_GET['message_id']) {
            throw new CHttpException(404, Yii::t('MessageModule.message', 'This message does not exist'));
        }
        else {
            $result=Message::model()->makeSpam($_GET['message_id']);
            if($result) {
                Yii::app()->user->setFlash(
                    YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('MessageModule.message', 'Message marked as "Spam"')
                );
            }
            else {
                Yii::app()->user->setFlash(
                    YFlashMessages::ERROR_MESSAGE,
                    Yii::t('MessageModule.message', 'When you mark "This is spam" An error has occurred. You might not have permission to perform this action')
                );
            }
            $this->redirect($this->createUrl('inbox/inbox'));
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
            $reply->recipient_id=$model->sender_id;
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