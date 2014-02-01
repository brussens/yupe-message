<?php

class MessageController extends yupe\components\controllers\FrontController
{
    public function init() {
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('application.modules.message.views.assets.css').'/messageModule.css'
            )
        );
        return parent::init();
    }
	public function actionInbox()
	{
        $dataProvider= Message::model()->inbox;
		$this->render('index', array('dataProvider'=>$dataProvider));
	}
    public function actionOutbox()
    {
        $dataProvider= Message::model()->outbox;
        $this->render('index', array('dataProvider'=>$dataProvider));
    }
    public function actionView() {
        $stat= Message::model()->countMessages();
        $model = Message::model()->messageView($_GET['message_id']);
        if (!$model) {
            throw new CHttpException(404, Yii::t('MessageModule.message', 'This message does not exist'));
        }
        $this->render('show',array('model'=>$model, 'stat'=>$stat));
    }
    public function actionCreate()
    {
        $model=new Message;
        $user=new User;
        // uncomment the following code to enable ajax-based validation

        if(isset($_POST['ajax']) && $_POST['ajax']==='message-CreateMessage-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if(isset($_POST['Message']))
        {
            $model->attributes=$_POST['Message'];
            if($model->validate())
            {
                if($model->save()) {
                    Yii::app()->user->setFlash(
                        YFlashMessages::SUCCESS_MESSAGE,
                        Yii::t('MessageModule.message', 'Message sent successfully!')
                    );
                }
            }
        }
        $this->render('create',array('model'=>$model, 'user'=>$user->findAll()));
    }
    public function toCut($str,$len=30,$div=" "){
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