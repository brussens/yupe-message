<?php
/**
 * Создано Hoswac ltd.
 * Пользователь: BrusSENS
 * Описание: Модель формы для ответа
 */

class ReplyForm extends Message {
    /**
     * @return array
     *
     * Наследуем метод от Message модели
     */
    public function rules() {
        return parent::rules();
    }

    /**
     * @return array
     *
     * Наследуем метод от Message модели
     */
    public function attributeLabels() {
        return parent::attributeLabels();
    }
}