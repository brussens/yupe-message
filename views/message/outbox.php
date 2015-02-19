<?php
/**
 * outbox.php - outbox message view file.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@nativeweb.ru>
 * @link http://nativeweb.ru
 * @copyright 2014 Native Web.
 * @package yupe.modules.message.views.message
 * @since 0.1α
 *
 */
?>
<div class="row">
    <?php
    $this->widget('zii.widgets.CListView', [
        'dataProvider' => $dataProvider,
        'itemView' => '_outbox',
        'template' => "{items}\n{pager}",
    ]);
    ?>
</div>