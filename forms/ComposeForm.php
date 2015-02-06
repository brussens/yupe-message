<?php
/**
 * Class ComposeForm - message form model.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message.controllers
 * @since 0.2.0α
 *
 */

class ComposeForm extends CFormModel
{
    public $recipient;
    public $body;

    public function rules()
    {
        return [
            ['recipient', 'required', 'message' => Yii::t('MessageModule.message', 'Recipient can not be empty')],
            ['body', 'required', 'message' => Yii::t('MessageModule.message', 'Message can not be empty')],
            ['body', 'length', 'min' => 1],
        ];
    }

    public function send()
    {
        if(!$this->hasErrors()) {
            $message = new Message;
            $message->recipient_id = $this->recipient;
            $message->sender_id = Yii::app()->user->id;
            $message->body = $this->body;
            $message->sent_at = new CDbExpression('NOW()');
            $message->is_read = Message::STATUS_NEW;
            $message->sender_del = Message::NOT_DELETED;
            $message->recipient_del = Message::NOT_DELETED;
            return $message->save(false);
        }
        return false;
    }
} 