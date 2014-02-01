<?php
/**
 * Создано Hoswac ltd.
 * Пользователь: BrusSENS
 * Дата: 26.01.14
 * Время: 4:49
 * Описание: 
 */

class MessageBackendController extends yupe\components\controllers\BackController
{
    public function actionIndex() {
        $this->render('index');
    }
    public function spam() {
        $this->render('spam');
    }
}