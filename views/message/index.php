<?php
/**
 * Файл отображения всех диалогов пользователя
 *
 * @author hoswac team <hello@hoswac.ru>
 * @link http://hoswac.ru
 * @copyright 2014 Hoswac ltd.
 * @package yupe.modules.message.views.message
 * @since 0.2-RC
 *
 */?>
<style>
.dialogue-item {
    border:1px solid #efefef;
    font-size:12px;
    position:relative;
}
.dialogue-item:hover {
    border:1px solid #efefef;
    cursor:pointer;
    background:#f5f5f5;
}
.dialogue-info {
    padding-left:0;
}
.dialogue-info .dialogue-info-time {
    font-size:10px;
    color:#666666;
}
.dialogue-item + .dialogue-item {
    border-top:none;
}
.dialogue-item:hover > .dialogue-remove {
    display:block;
}
.dialogue-remove {
    display:none;
    background:none;
    border:none;
    position:absolute;
    top:5px;
    right:10px;
    font-size:18px;
    color:#aaaaaa;
}
.dialogue-remove:hover {
    color: #ff4600;
}
</style>
<div class="row">
<?php
    $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$dataProvider,
        'itemView'=>'_dialogue',
        //'template'=>$this->renderPartial('_dialogue_template',array(),true),
    ));
?>
</div>