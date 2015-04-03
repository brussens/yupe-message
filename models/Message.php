<?php
/**
 * Class Message - the model {{message_message}} table.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@nativeweb.ru>
 * @link http://nativeweb.ru
 * @copyright 2014 Native Web.
 * @package yupe.modules.message.models
 * @since 0.1α
 *
 */

class Message extends yupe\models\YModel {

    // status read
    const STATUS_READ = 1;

    // status not read
    const STATUS_NEW = 2;

    const NOT_DELETED = 1;

    const DELETED = 2;

    const NOT_SPAM = 1;

    const SPAM = 2;


    /**
     * @param null|string $className
     * @return $this
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string
     */
    public function tableName()
    {
        return '{{message_message}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return[
            'id' => Yii::t('MessageModule.message', 'ID'),
            'sent_at' => Yii::t('MessageModule.message', 'Sent date'),
            'recipient_id' => Yii::t('MessageModule.message', 'Recipient'),
            'sender_id' => Yii::t('MessageModule.message', 'Sender'),
            'body' => Yii::t('MessageModule.message', 'Body'),
            'is_read' => Yii::t('MessageModule.message', 'Read'),
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['sender_id, recipient_id, body', 'required'],
            ['sent_at, is_read', 'safe'],
            ['sender_id, recipient_id', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array
     */
    public function relations()
    {
        return [
            'sender'   => array(self::BELONGS_TO, 'User', 'sender_id'),
            'recipient'   => array(self::BELONGS_TO, 'User', 'recipient_id'),
        ];
    }

    /**
     * Сообщение входящее
     *
     * @return bool
     */
    public function getIsInbox()
    {
        return Yii::app()->user->id === $this->recipient_id;
    }

    /**
     * Сообщение исходящее.
     *
     * @return bool
     */
    public function getIsOutbox()
    {
        return Yii::app()->user->id === $this->sender_id;
    }

    /**
     * @return bool
     */
    public function getIsNew()
    {
        return $this->is_read == self::STATUS_NEW;
    }

    /**
     * Проверка доступа к сообщению.
     * @return bool
     */
    public function getHasAccess()
    {
        if($this->sender_id === Yii::app()->user->id || $this->recipient_id === Yii::app()->user->id) {
            return true;
        }
        return false;
    }

    /**
     * Список статусов.
     *
     * @return array
     */
    public function getStatusList()
    {
        return [
            self::STATUS_NEW => Yii::t('MessageModule.message', 'No'),
            self::STATUS_READ => Yii::t('MessageModule.message', 'Yes'),
        ];
    }

    /**
     * Получение статуса сообщения
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->getStatusList()[$this->is_read];
    }

    /**
     * Проверка сообщения на статус спама.
     * @return bool
     */
    public function getIsSpam()
    {
        return $this->is_spam == self::SPAM;
    }

    /**
     * @param int $pageSize
     * @return CActiveDataProvider
     */
    public function search($pageSize = 10)
    {
        $criteria = new CDbCriteria();

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.sent_at', $this->sent_at, true);
        $criteria->compare('t.sender_id', $this->sender_id, true);
        $criteria->compare('t.recipient_id', $this->recipient_id, true);
        $criteria->with = ['sender', 'recipient'];

        return new CActiveDataProvider(get_class($this), array(
            'criteria'   => $criteria,
            'pagination' => array(
                'pageSize' => $pageSize,
            ),
            'sort'       => array(
                'defaultOrder' => 'sent_at DESC',
            )
        ));
    }

    /**
     * Помечает сообщение, как прочитанное.
     *
     * @throws CDbException
     */
    public function markAsRead()
    {
        if($this->getIsNew() && $this->getIsInbox()) {
            $this->is_read = self::STATUS_READ;
            $this->update(['is_read']);
        }
    }

    /**
     * Проверка, удалено ли сообщение отправителем
     * @return bool
     */
    public function isSenderDeleted()
    {
        return $this->sender_del === self::DELETED;
    }

    /**
     * Првоерка, удалено ли сообщение получателем.
     * @return bool
     */
    public function isRecipientDeleted()
    {
        return $this->recipient_del === self::DELETED;
    }

    /**
     * Помечает сообщение удалённым.
     * @throws CHttpException
     */
    public function markAsDelete()
    {
        if($this->getIsInbox()){
            if($this->isRecipientDeleted()) {
                throw new CHttpException(404, Yii::t('MessageModule.message', 'This message already removed'));
            }
            else {
                $this->recipient_del = self::DELETED;
            }
        }
        elseif($this->getIsOutbox()) {
            if($this->isSenderDeleted()) {
                throw new CHttpException(404, Yii::t('MessageModule.message', 'This message already removed'));
            }
            else {
                $this->sender_del = self::DELETED;
            }
        }
        if($this->update()) {
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
    }

    /**
     * Помечает сообщение, как спам.
     * @throws CDbException
     */
    public function markAsSpam()
    {
        if($this->getIsInbox() && !$this->getIsSpam()){
            $this->is_spam = self::SPAM;
            if($this->update()) {
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
    }

    /**
     * Помечает сообщение, как не спам.
     * @throws CDbException
     */
    public function markAsNotSpam()
    {
        if($this->getIsInbox() && $this->getIsSpam()){

            $this->is_spam = self::NOT_SPAM;

            if($this->update()) {
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
    }

    /**
     * Спам сообщения
     * @return CActiveDataProvider
     */
    public static function spamBox()
    {
        return new CActiveDataProvider(__CLASS__, [
            'criteria' => [
                'condition' => 'recipient_id = :user_id AND recipient_del = :recipient_del AND is_spam = :is_spam',
                'params' => [
                    ':user_id' => Yii::app()->getUser()->getId(),
                    ':recipient_del' => self::NOT_DELETED,
                    ':is_spam' => self::SPAM,
                ],
                'order' => 'sent_at DESC',
                'with' => ['sender'],
            ],

            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    }

    /**
     * Отправленные сообщения
     * @return CActiveDataProvider
     */
    public static function outbox()
    {
        return new CActiveDataProvider(__CLASS__, [
            'criteria' => [
                'condition' => 'sender_id = :user_id AND sender_del = :sender_del',
                'params' => [
                    ':user_id' => Yii::app()->getUser()->getId(),
                    ':sender_del' => self::NOT_DELETED,
                ],
                'order' => 'sent_at DESC',
                'with' => ['recipient'],
            ],

            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    }

    /**
     * Входящие сообщения
     * @return CActiveDataProvider
     */
    public static function inbox()
    {
        return new CActiveDataProvider(__CLASS__, [
            'criteria' => [
                'condition' => 'recipient_id = :user_id AND recipient_del = :recipient_del AND is_spam = :is_spam',
                'params' => [
                    ':user_id' => Yii::app()->getUser()->getId(),
                    ':recipient_del' => self::NOT_DELETED,
                    ':is_spam' => self::NOT_SPAM,
                ],
                'order' => 'sent_at DESC',
                'with' => ['sender'],
            ],

            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    }
}