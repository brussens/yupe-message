<?php
/**
 * Class Message - the model {{message_message}} table.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message.models
 * @since 0.1-α
 *
 */

class Message extends yupe\models\YModel {

    // status read
    const STATUS_READ = 1;

    // status not read
    const STATUS_NEW = 2;

    const NOT_DELETED = 2;

    const DELETED = 2;

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

    public function getIsInbox()
    {
        return Yii::app()->user->id === $this->recipient_id;
    }

    public function getIsNew()
    {
        return $this->is_read == self::STATUS_NEW;
    }

    public function getHasAccess()
    {
        if($this->sender_id === Yii::app()->user->id || $this->recipient_id === Yii::app()->user->id) {
            return true;
        }
        return false;
    }

    public function getStatusList()
    {
        return [
            self::STATUS_NEW => Yii::t('MessageModule.message', 'No'),
            self::STATUS_READ => Yii::t('MessageModule.message', 'Yes'),
        ];
    }

    public function getStatus()
    {
        return $this->getStatusList()[$this->is_read];
    }

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
}