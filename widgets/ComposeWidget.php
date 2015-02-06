<?php
/**
 * Class ComposeWidget - the widget of compose form.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message.models
 * @since 0.2-α
 *
 */

class ComposeWidget extends CWidget
{
    public $recipient;

    public function run()
    {
        $model = new ComposeForm;
        $model->recipient = $this->recipient;

        $this->controller->renderPartial('/message/compose', [
            'model' => $model
        ]);
    }
} 