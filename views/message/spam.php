<?php
/**
 * spam.php - spam messages view file.
 *
 * @author BrusSENS (Dmitry Brusenskiy) <brussens@nativeweb.ru>
 * @link http://nativeweb.ru
 * @copyright 2014 Native Web.
 * @package yupe.modules.message.views.message
 * @since 0.1α
 *
 */
?>
<script>
    /*$(document).ready(function(){
        $('.message-preview-item').on('hover', function(){
            alert($(this).css('height'));
        });
    });*/
</script>
<div class="row">
    <?php
    $this->widget('zii.widgets.CListView', [
        'dataProvider' => $dataProvider,
        'itemView' => '_spam',
        'template' => "{items}\n{pager}",
    ]);
    ?>
</div>