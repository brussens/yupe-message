<?php

/**
 * Class m150219_1424325633_add_spam - добавление колонки is_spam.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@nativeweb.ru>
 * @link http://nativeweb.ru
 * @copyright 2014 Native Web.
 * @package yupe.modules.message.install.migrations
 * @since 0.2.0β
 *
 */

class m150219_1424325633_add_spam extends yupe\components\DbMigration
{
    /**
     *
     * @return bool|void
     *
     */
    public function safeUp()
    {
        $this->addColumn('{{message_message}}', 'is_spam', 'integer NOT NULL');
        $this->createIndex("ix_{{message_message}}_is_spam", '{{message_message}}', "is_spam", false);
    }

    /**
     *
     * @return bool|void
     *
     */
    public function safeDown()
    {
        $this->dropIndex('ix_{{message_message}}_is_spam', '{{message_message}}');
        $this->dropColumn('{{message_message}}', 'is_spam');
    }
} 