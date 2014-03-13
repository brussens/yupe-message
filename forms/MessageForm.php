<?php
/**
 * Класс модели для формы создания нового сообщения
 */

class MessageForm extends Message {
    /**
     * @var
     *
     * Никнейм пользователя.
     */
    public $nick_name;

    public function rules(){
        $rules=parent::rules();
        return CMap::mergeArray($rules,array(
            array('nick_name', 'required'),
            array('nick_name', 'recipientValidate'),
            array('nick_name', 'safe'),
        ));
    }

    public function beforeSave() {
        if(empty($this->subject))
        $this->subject=Yii::t('MessageModule.message', 'No subject');
        return parent::beforeSave();
    }

    public function attributeLabels() {
        $labels=parent::attributeLabels();
        return CMap::mergeArray($labels,array(
            'nick_name' => Yii::t('MessageModule.message', 'Recipient nickname'),
        ));
    }

    /**
     * Метод валидации пользователя по введённому nick_name.
     */
    public function recipientValidate() {
        //Вытаскиваем данные пользователя
        $user=User::model()->findByAttributes(array('nick_name'=>$this->nick_name));
        if($user) {//если такой пользователь существует
            if(Yii::app()->user->id==$user->id) {//проверяем, не отправляет ли он сообщение самому себе
                $this->addError('nick_name', Yii::t('MessageModule.message', 'You can not send messages to yourself'));//если отправляет себе, то выдаём ошибку валидации
            }
            else {//если отправляет другому пользователю
                $this->recipient_id=$user->id;//записываем id получателя в соответствующее свойство
            }
        }
        else {//если пользователя с введёным nick_name не существует
            $this->addError('nick_name', Yii::t('MessageModule.message', 'This user does not exist'));//выдаём ошибку валидации
        }
    }

}