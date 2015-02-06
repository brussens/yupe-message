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

    // TODO
    public function getHasAccess()
    {

    }
}