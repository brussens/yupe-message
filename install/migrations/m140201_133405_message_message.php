<?php
/**
 * Message install migration
 * Класс миграций для модуля Message:
 *
 * @category YupeMigration
 * @package  yupe.modules.message.install.migrations
 * @author   BrusSENS <brussens@hoswac.com>
 * @license  BSD https://raw.github.com/yupe/yupe/master/LICENSE
 * @link     http://yupe.ru
 **/
class m140201_133405_message_message extends yupe\components\DbMigration
{
    /**
     * Функция настройки и создания таблицы:
     *
     * @return null
     **/
    public function safeUp()
    {
        $this->createTable(
            '{{message_message}}',
            array(
                'id'                => 'pk',
                'subject'           => 'varchar(150)',
                'body'              => 'text NOT NULL',
                'send_date'         => 'datetime NOT NULL',
                'sender_id'         => 'INT(11) NOT NULL',
                'recipient_id'      => 'INT(11) NOT NULL',
                'is_spam'           => "BIT(1) NOT NULL DEFAULT b'0'",
                'is_read'           => "BIT(1) NOT NULL DEFAULT b'0'",
                'sender_del'        => "BIT(1) NOT NULL DEFAULT b'0'",
                'recipient_del'     => "BIT(1) NOT NULL DEFAULT b'0'",
            ),
            $this->getOptions()
        );

        //ix
        $this->addForeignKey('fk_recipient', '{{message_message}}', 'recipient_id',
            '{{user_user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_sender', '{{message_message}}', 'sender_id',
            '{{user_user}}', 'id', 'CASCADE', 'CASCADE');
    }
    /**
     * Функция удаления таблицы:
     *
     * @return null
     **/
    public function safeDown()
    {
        $this->dropTableWithForeignKeys('{{message_message}}');
    }
}
