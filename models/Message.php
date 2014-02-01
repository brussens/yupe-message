<?php

/**
 * This is the model class for table "{{message_message}}".
 *
 * The followings are the available columns in table '{{message_message}}':
 * @property string $id
 * @property string $subject
 * @property string $body
 * @property string $send_date
 * @property integer $sender_id
 * @property integer $recipient_id
 * @property integer $is_spam
 * @property integer $is_read
 * @property integer $sender_del
 * @property integer $recipient_del
 *
 * @property string $nick_name
 *
 * The followings are the available model relations:
 * @property UserUser $sender
 * @property UserUser $recipient
 */
class Message extends CActiveRecord
{
    public $nick_name;
    const SPAM=1;
    const NOT_SPAM=0;
    const READ=1;
    const NOT_READ=0;
    const DROP=1;
    const NOT_DROP=0;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{message_message}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('body, subject, nick_name', 'required'),
			array('sender_id, recipient_id, is_spam, is_read, sender_del, recipient_del', 'numerical', 'integerOnly'=>true),
			array('subject', 'length', 'max'=>150),
            array('send_date','default', 'value'=>new CDbExpression('NOW()')),
            array('sender_id','default', 'value'=>Yii::app()->user->id),
            array('nick_name', 'recipientValidate'),
            //array('recipient_id','default', 'value'=>2),
            array('body','filter', 'filter'=>array($obj=new CHtmlPurifier(),'purify')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
            array('nick_name', 'safe'),
			array('id, subject, body, send_date, sender_id, recipient_id, is_spam, is_read, sender_del, recipient_del', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'sender' => array(self::BELONGS_TO, 'User', 'sender_id'),
			'recipient' => array(self::BELONGS_TO, 'User', 'recipient_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'subject' => Yii::t('MessageModule.message', 'Subject'),
			'body' => Yii::t('MessageModule.message', 'Content'),
			'send_date' => Yii::t('MessageModule.message', 'Dispatch date'),
			'sender_id' => Yii::t('MessageModule.message', 'Sender ID'),
            'nick_name' => Yii::t('MessageModule.message', 'Recipient nickname'),
			'recipient_id' => Yii::t('MessageModule.message', 'Recipient ID'),
			'is_spam' => Yii::t('MessageModule.message', 'Marked by the recipient as "Spam"'),
			'is_read' => Yii::t('MessageModule.message', 'Read by the recipient'),
			'sender_del' => Yii::t('MessageModule.message', 'Deleted sender'),
			'recipient_del' => Yii::t('MessageModule.message', 'Deleted recipient'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('send_date',$this->send_date,true);
		$criteria->compare('sender_id',$this->sender_id);
		$criteria->compare('recipient_id',$this->recipient_id);
		$criteria->compare('is_spam',$this->is_spam);
		$criteria->compare('is_read',$this->is_read);
		$criteria->compare('sender_del',$this->sender_del);
		$criteria->compare('recipient_del',$this->recipient_del);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function messageView($id) {
        $message=$this->findByPk($id);
        if($message->is_read==self::NOT_READ) {
            $message->is_read=self::READ;
            $message->update(array('is_read'));
        }
        return $message;
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MessageMessage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Метод валидации пользователя по введённому nick_name
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

    /**
     * @param string $type = inbox, sent, spam, drop
     * По умолчанию inbox
     * Метод получения количества сообщений
     * inbox - входящие
     * sent - отправленные
     * spam - помеченные пользователем, как спам.
     * drop - помеченные пользователем, как удалённые.
     */
    public function countMessages() {
        $stat=array();
        $criteriaSpam = new CDbCriteria;
        $criteriaSpam->condition = 'recipient_id = ' . Yii::app()->user->id;
        $criteriaSpam->addCondition("is_spam = " . self::SPAM);
        $criteriaSpam->addCondition("recipient_del = " . self::NOT_DROP);
        $stat['spam']=$this->count($criteriaSpam);

        $criteriaDrop = new CDbCriteria;
        $criteriaDrop->condition = 'recipient_id = ' . Yii::app()->user->id;
        $criteriaDrop->addCondition("recipient_del = " . self::DROP);
        $stat['drop']=$this->count($criteriaDrop);

        return $stat;
    }
    public function getInboxcount() {
        $criteria= new CDbCriteria;
        $criteria->condition = 'recipient_id = ' . Yii::app()->user->id;
        $criteria->addCondition("is_spam = " . self::NOT_SPAM);
        $criteria->addCondition("recipient_del = " . self::NOT_DROP);
        $criteria->addCondition("is_read = " . self::NOT_READ);
        return $this->count($criteria);
    }
    public function getOutboxcount() {
        $criteria = new CDbCriteria;
        $criteria->condition = 'sender_id = ' . Yii::app()->user->id;
        $criteria->addCondition("sender_del = " . self::NOT_DROP);
        return $this->count($criteria);
    }
    public function getInbox() {
        $criteria = new CDbCriteria;
        $criteria->condition = 'recipient_id = ' . Yii::app()->user->id;
        $criteria->addCondition("is_spam = " . self::NOT_SPAM);
        $criteria->addCondition("recipient_del = " . self::NOT_DROP);
        $criteria->with = array('sender'=>array('alias'=>'author'));
        $criteria->order='send_date DESC';
        $dataProvider=new CActiveDataProvider($this, array(
            'pagination'=>array(
                'pageSize'=>3,
            ),
            'criteria'=>$criteria,
        ));
        return $dataProvider;
    }
    public function getOutbox() {
        $criteria = new CDbCriteria;
        $criteria->condition = 'sender_id = ' . Yii::app()->user->id;
        $criteria->addCondition("sender_del = " . self::NOT_DROP);
        $criteria->order='send_date DESC';
        $dataProvider=new CActiveDataProvider($this, array(
            'pagination'=>array(
                'pageSize'=>3,
            ),
            'criteria'=>$criteria,
        ));
        return $dataProvider;
    }

    /**
     * @param string $type = inbox, sent, spam, drop
     * По умолчанию inbox
     * Метод получения списка сообщений.
     * inbox - входящие
     * sent - отправленные
     * spam - помеченные пользователем, как спам.
     * drop - помеченные пользователем, как удалённые.
     * @param boolean $dataProvider
     * По умолчанию true
     * При false возвращает criteria
     *
     */
    public function giveMessages($type='inbox', $dataProvider=true) {
        $criteria = new CDbCriteria;
        switch ($type) {
            case "spam":
                $criteria->condition = 'recipient_id = ' . Yii::app()->user->id;
                $criteria->addCondition("is_spam = " . self::SPAM);
                $criteria->addCondition("recipient_del = " . self::NOT_DROP);
                break;
            case "drop":
                $criteria->condition = 'recipient_id = ' . Yii::app()->user->id;
                $criteria->addCondition("recipient_del = " . self::DROP);
                break;
        }
        $criteria->order='send_date DESC';
        $dataProvider=new CActiveDataProvider($this, array(
            'pagination'=>array(
                'pageSize'=>3,
            ),
            'criteria'=>$criteria,
        ));
        return $dataProvider;
    }
    public function getRelation($actionId) {
        switch($actionId) {
            case "outbox":
                return $this->recipient;
                break;
            case "inbox":
                return $this->sender;
                break;
        }
    }
    public function getIsRead() {
        if($this->is_read!=0) {
            return true;
        }
        else {
            return false;
        }
    }
}
