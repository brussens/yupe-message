<?php
/**
 * Class MessageController
 *
 * Класс контроллера для Создания сообщений
 *
 */
class MessageController extends BaseController {
    /**
     * Экшн создания НОВОГО сообщения
     */
    public function actionCreate() {
        $model=new MessageForm;

        $user=new User;

        if(isset($_POST['ajax']) && $_POST['ajax']==='message-CreateMessage-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if(isset($_POST['MessageForm']))
        {
            $model->attributes=$_POST['MessageForm'];
            if($model->validate())
            {
                if($model->save()) {
                    Yii::app()->user->setFlash(
                        YFlashMessages::SUCCESS_MESSAGE,
                        Yii::t('MessageModule.message', 'Message sent successfully!')
                    );
                    $this->redirect($this->createUrl('inbox/inbox'));//TODO: Сделать правильный редирект
                }
            }
        }
        $this->render('create',array('model'=>$model, 'user'=>$user->findAll()));
    }
}