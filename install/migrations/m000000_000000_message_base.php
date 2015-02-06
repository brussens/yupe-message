<?php

/**
 * Class m000000_000000_message_base - migrations class {{message_message}} table.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message.install.migrations
 * @since 0.2.0α
 *
 */

class m000000_000000_message_base extends yupe\components\DbMigration
{
    /**
     * Creating tables.
     *
     * @return bool|void
     */
    public function safeUp()
    {
        // Messages table
        $this->createTable(
            '{{message_message}}',
            [
                'id' => 'pk',
                'body' => 'text NOT NULL',
                'recipient_id' => 'integer NOT NULL',
                'sender_id' => 'integer NOT NULL',
                'sent_at' => 'datetime NOT NULL',
                'is_read' => 'integer NOT NULL',
                'sender_del' => 'integer NOT NULL',
                'recipient_del' => 'integer NOT NULL',
            ],
            $this->getOptions()
        );

        //Indexes
        $this->createIndex("ix_{{message_message}}_recipient", '{{message_message}}', "recipient_id", false);
        $this->createIndex("ix_{{message_message}}_sender", '{{message_message}}', "sender_id", false);
        $this->createIndex("ix_{{message_message}}_is_read", '{{message_message}}', "is_read", false);
        $this->createIndex("ix_{{message_message}}_sender_del", '{{message_message}}', "sender_del", false);
        $this->createIndex("ix_{{message_message}}_recipient_del", '{{message_message}}', "recipient_del", false);

        // Foreign keys
        $this->addForeignKey("fk_{{message_message}}_recipient", '{{message_message}}', 'recipient_id', '{{user_user}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey("fk_{{message_message}}_sender", '{{message_message}}', 'sender_id', '{{user_user}}', 'id', 'CASCADE', 'NO ACTION');
    }

    /**
     *
     * Deleting tables.
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTableWithForeignKeys('{{message_message}}');
    }
} 