<?php
/**
 * Основная модель модуля Message.
 *
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
 * The followings are the available model relations:
 * @property UserUser $sender
 * @property UserUser $recipient
 *
 *
 */
class Message extends CActiveRecord
{
    /**
     * Константа статуса "Это спам"
     */
    const SPAM=1;

    /**
     * Константа статуса "Это не спам"
     */
    const NOT_SPAM=0;

    /**
     * Константа "Сообщение прочитано"
     */
    const READ=1;

    /**
     * Константа "Сообщение не прочитано"
     */
    const NOT_READ=0;

    /**
     * Константа "Сообщение удалено"
     */
    const DROP=1;

    /**
     * Константа "Сообщение не удалено"
     */
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
			array('body', 'required'),
			array('sender_id, recipient_id, is_spam, is_read, sender_del, recipient_del, reply_to', 'numerical', 'integerOnly'=>true),
			array('subject', 'length', 'max'=>150),
            array('send_date','default', 'value'=>new CDbExpression('NOW()')),
            array('sender_id','default', 'value'=>Yii::app()->user->id),
            array('body','filter', 'filter'=>array($obj=new CHtmlPurifier(),'purify')),
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
			'recipient_id' => Yii::t('MessageModule.message', 'Recipient ID'),
			'is_spam' => Yii::t('MessageModule.message', 'Marked by the recipient as "Spam"'),
			'is_read' => Yii::t('MessageModule.message', 'Read by the recipient'),
			'sender_del' => Yii::t('MessageModule.message', 'Deleted sender'),
			'recipient_del' => Yii::t('MessageModule.message', 'Deleted recipient'),
            'reply_to' => Yii::t('MessageModule.message', 'Parent message'),
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
        $criteria->compare('reply_to',$this->reply_to);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     * @param $id
     *
     * @return CActiveRecord
     *
     * Метод для получения данных определённого сообщения.
     */
    public function messageView($id) {//Получаем id просматриваемого сообщения.
        $message=$this->findByPk($id);//Получаем данные просматриваемого сообщения.
        if($message->recipient_id===Yii::app()->user->id) {//Если его читает получатель
            if(!$this->getIsRead()) {//Если получатель ещё не читал сообщение
                $message->is_read=self::READ;//Делаем его прочитанным.
                $message->update(array('is_read'));//Обновляем информацию о сообщении.
            }
        }
        return $message;// И возращаем модель для дальнейшего показа сообщения.
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
     * @return string
     *
     * Метод получения количества непрочитанных входящих сообщений.
     *
     * Исп.: Message::model()->inboxcount;
     */
    public function getInboxcount() {
        $criteria= new CDbCriteria;
        $criteria->condition = 'recipient_id = ' . Yii::app()->user->id;
        $criteria->addCondition("is_spam = " . self::NOT_SPAM);
        $criteria->addCondition("recipient_del = " . self::NOT_DROP);
        $criteria->addCondition("is_read = " . self::NOT_READ);
        return $this->count($criteria);
    }

    /**
     * @return string
     *
     * Метод получения количества отправленных сообщений.
     *
     * Исп.: Message::model()->outboxcount;
     */
    public function getOutboxcount() {
        $criteria = new CDbCriteria;
        $criteria->condition = 'sender_id = ' . Yii::app()->user->id;
        $criteria->addCondition("sender_del = " . self::NOT_DROP);
        return $this->count($criteria);
    }

    /**
     * @return CActiveDataProvider
     *
     * Метод получения входящих сообщений.
     *
     * Исп.: Message::model()->inbox;
     */
    public function getInbox() {
        $criteria = new CDbCriteria;
        $criteria->condition = 'recipient_id = ' . Yii::app()->user->id;
        $criteria->addCondition("is_spam = " . self::NOT_SPAM);
        $criteria->addCondition("recipient_del = " . self::NOT_DROP);
        $criteria->with = array('sender');
        $criteria->order='send_date DESC';
        $dataProvider=new CActiveDataProvider($this, array(
            'pagination'=>array(
                'pageSize'=>Yii::app()->controller->module->messagePerPage,
            ),
            'criteria'=>$criteria,
        ));
        return $dataProvider;
    }

    /**
     * @return CActiveDataProvider
     *
     * Метод получения отправленных сообщений.
     *
     * Исп.: Message::model()->outbox;
     */
    public function getOutbox() {
        $criteria = new CDbCriteria;
        $criteria->condition = 'sender_id = ' . Yii::app()->user->id;
        $criteria->addCondition("sender_del = " . self::NOT_DROP);
        $criteria->with = array('recipient');
        $criteria->order='send_date DESC';
        $dataProvider=new CActiveDataProvider($this, array(
            'pagination'=>array(
                'pageSize'=>Yii::app()->controller->module->messagePerPage,
            ),
            'criteria'=>$criteria,
        ));
        return $dataProvider;
    }

    /**
     * @return CActiveDataProvider
     *
     * Метод получения спама для администратора.
     *
     * Исп.: Message::model()->spamAdm;
     */
    public function getSpamAdm() {
        $criteria = new CDbCriteria;
        $criteria->addCondition("is_spam = " . self::SPAM);
        $criteria->with = array('recipient', 'sender');
        $criteria->order='send_date DESC';
        $dataProvider=new CActiveDataProvider($this, array(
            'pagination'=>array(
                'pageSize'=>Yii::app()->controller->module->messagePerPage,
            ),
            'criteria'=>$criteria,
        ));
        return $dataProvider;
    }

    /**
     * @return bool
     *
     * Метод проверки, прочитано сообщение или нет.
     *
     * Исп:. $this->isRead;
     */
    public function getIsRead() {
        if($this->is_read!=self::NOT_READ) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param $id
     *
     * @return bool
     *
     * Метод удаления отправленого сообщения
     * Удаления как такового не происходит.
     * Сообщение только помечается, как удалённое Отправителем.
     */
    public function removeOutbox($id) {
        $message=$this->findByPk($id);
        if($message->sender_id===Yii::app()->user->id) {
            $message->sender_del=self::DROP;
            $message->update();
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param $id
     *
     * @return bool
     *
     * Метод удаления входящего сообщения.
     * Удаления как такового не происходит.
     * Сообщение только помечается, как удалённое получателем.
     */
    public function removeInbox($id) {
        $message=$this->findByPk($id);
        if($message->recipient_id===Yii::app()->user->id) {
            $message->recipient_del=self::DROP;
            $message->update();
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param $id
     *
     * @return bool
     *
     * Метод пометки сообщения, как спам.
     */
    public function makeSpam($id) {
        //Получаем сообщение по его ID.
        $message=$this->findByPk($id);
        //Проверяем, кто отмечает его, как спам.
        if($message->recipient_id===Yii::app()->user->id) {
            //Помечаем сообщение, как спам.
            $message->is_spam=self::SPAM;
            //Обновляем сообщение.
            $message->update();
            //Возвращаем успешное выполнение операции.
            return true;
        }
        else {//Если его помечает кто то другой.
            //Возвращаем провал операции.
            return false;
        }
    }
}
