<?php
/**
 * Создано Hoswac ltd.
 * Пользователь: BrusSENS
 * Дата: 31.01.14
 * Время: 16:12
 * Описание: Класс виджета для форматирования даты.
 * Исходный код: http://www.dbhelp.ru/people-date-widget/page/
 */

class PeopleDate extends YWidget {
    public $date;
    public $type = 1;

    public function init()
    {
        if (!empty($this->date)) {
            switch ($this->type) {
                case 1: {
                    echo $this->dateFormat($this->date);
                    break;
                }
                case 2: {
                    echo $this->getTimeAgo($this->date);
                    break;
                }
            }
        }
    }

    function dateFormat($string, $format="%e %b %Y в %H:%M", $lang = 'ru')
    {
        $strtime=strtotime($string);
        $timeAgo = time() - $strtime;
        $deftime=array(24=>3600*24, 48=>3600*48);
            if (substr(PHP_OS,0,3) == 'WIN') {
                $_win_from = array ('%e',  '%T',       '%D');
                if($timeAgo<$deftime[24]) {
                    $format=Yii::t('MessageModule.message', 'Today at') . " %H:%M";
                }
                elseif($deftime[24]<$timeAgo && $timeAgo<$deftime[48]) {
                    $format=Yii::t('MessageModule.message', 'Yesterday at') . " %H:%M";
                }
                else {
                    $_win_to   = array ('%#d', '%H:%M:%S', '%m/%d/%y');
                }

                $format = str_replace($_win_from, $_win_to, $format);
            }

            if($string != '') {
                $out = strftime($format, $strtime);
            } else {
                $out = '';
            }

            $strFrom = array(
                'january', 		'jan',
                'february', 	'feb',
                'march', 		'mar',
                'april', 		'apr',
                'may', 			'may',
                'june',  	   'jun',
                'july', 		'jul',
                'august', 		'aug',
                'september',	'sep',
                'october',		'oct',
                'november',		'nov',
                'december',		'dec',
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday',
                'mon',
                'tue',
                'wed',
                'thu',
                'fri',
                'sat',
                'sun',
            );
            $strTo = array('ru' => array(
                'январь',	'янв',
                'февраль',	'фев',
                'март',		'мар',
                'апрель',	'апр',
                'май',		'май',
                'июнь',		'июн',
                'июль',		'июл',
                'август',	'фвг',
                'сентябрь',	'сен',
                'октябрь',	'окт',
                'ноябрь',	'ноя',
                'декабрь',	'дек',
                'понедельник',
                'вторник',
                'среда',
                'четверг',
                'пятница',
                'суббота',
                'воскресенье',
                'пн',
                'вт',
                'ср',
                'чт',
                'пт',
                'сб',
                'вс',
            ),
                'ua' => array(
                    'Січень','Січ',
                    'Лютий',		'Лют',
                    'Березень',		'Бер',
                    'Квітень', 		'Кві',
                    'Травень',		'Тра',
                    'Червень',		'Чер',
                    'Липень',		'Лип',
                    'Серпень',		'Сер',
                    'Вересень',		'Вер',
                    'Жовтень',		'Жов',
                    'Листопад',		'Лис',
                    'Грудень',		'Грд',
                    'Понеділок',
                    'Вівторок',
                    'Середа',
                    'Четвер',
                    'П\'ятниця',
                    'Субота',
                    'Неділя',
                    'Пн',
                    'Вт',
                    'Ср',
                    'Чт',
                    'Пт',
                    'Сб',
                    'Нд',
                )

            );

            $outOld = $out;
            $out = str_replace($strFrom, $strTo[$lang], strtolower($out));
            if ($out == strtolower($outOld)){
                $out = $outOld;
            }
            $out = str_replace('Май.', 'мая', $out);
            return $out;
    }

    function dateRidN2R($str)
    {
        $arrFrom = array(
            'январь',
            'февраль',
            'март',
            'апрель',
            'май',
            'июнь',
            'июль',
            'август',
            'сентябрь',
            'октябрь',
            'ноябрь',
            'декабрь',	 );
        $arrTo = array(
            'января',
            'февраля',
            'марта',
            'апреля',
            'мая',
            'июня',
            'июля',
            'августа',
            'сентября',
            'октября',
            'ноября',
            'декабря');
        $str = str_replace($arrFrom, $arrTo,  strtolower($str));
        return $str;
    }

    /**
     * Переводим TIMESTAMP в формат вида: 5 дн. назад
     * или 1 мин. назад и тп.
     *
     * @param unknown_type $date_time
     * @return unknown
     */
    function getTimeAgo($date_time)
    {
        $timeAgo = time() - strtotime($date_time);
        $timePer = array(
            'day' 	=> array(3600 * 24, 'дн.'),
            'hour' 	=> array(3600, ''),
            'min' 	=> array(60, 'мин.'),
            'sek' 	=> array(1, 'сек.'),
        );
        foreach ($timePer as $type =>  $tp) {
            $tpn = floor($timeAgo / $tp[0]);
            if ($tpn){

                switch ($type) {
                    case 'hour':
                        if (in_array($tpn, array(1, 21))){
                            $tp[1] = 'час';
                        }elseif (in_array($tpn, array(2, 3, 4, 22, 23)) ) {
                            $tp[1] = 'часa';
                        }else {
                            $tp[1] = 'часов';
                        }
                        break;
                }
                return $tpn.' '.$tp[1].' назад';
            }
        }

    }
}