<?php
/**
 * inbox.php - inbox message view file.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message.views.message
 * @since 0.1-α
 *
 */
?>
<div class="row">
    <?php
    $this->widget('zii.widgets.CListView', [
        'dataProvider' => $dataProvider,
        'itemView' => '_inbox',
        'template' => "{items}\n{pager}",
    ]);
    ?>
</div>