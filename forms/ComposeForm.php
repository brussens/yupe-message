<?php
/**
 * Class ComposeForm - Форма для сообщений.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@nativeweb.ru.ru>
 * @link http://nativeweb.ru
 * @copyright 2014 Native Web.
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
            ['body', 'clean'],
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
            $message->is_spam = Message::NOT_SPAM;
            return $message->save(false);
        }
        return false;
    }

    public function clean($attribute)
    {
        $options = [
            'HTML.AllowedElements' => [
                'a'
            ],
            'URI.AllowedSchemes' => [
                'http' => true,
                'https' => true
            ],
            //'URI.Munge' => true,
            'AutoFormat.Linkify' => true,
            //'URI.HostBlacklist' => '', TODO: подключить API DR.WEB для чёрных ссылок
        ];

        $purifier = new CHtmlPurifier();
        $purifier->options = $options;
        $this->$attribute = $purifier->purify($this->$attribute);

        if(Yii::app()->getModule('message')->censure) {
            Yii::import('message.vendor.php-censure.Censure');
            $this->$attribute = Censure::parse($this->$attribute, '10', '', false, ':censored:');
        }

        return $this->$attribute;
    }
} 