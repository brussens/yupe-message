/**
 * DateFormat is a jQuery plugin that makes it easy to format timestamps and automatically
 * updating fuzzy timestamps (e.g. "4 minutes ago").
 *
 * @name dateFormatter
 * @version 0.0.1
 * @requires jQuery v1.2.3+
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 *
 * For usage and examples, visit:
 * http://www.itlessons.info/javascript/jquery-plugin-with-date-formatting-and-timeago/
 *
 * Copyright (c) 2013, www.itlessons.info
 */
(function ($, moment) {

    $.dateFormat = function (timestamp) {
        if (timestamp instanceof Date) {
            return $.dateFormat.format(timestamp);
        } else if (typeof timestamp === "string") {
            return $.dateFormat.format($.dateFormat.parse(timestamp));
        } else if (typeof timestamp === "number") {
            return $.dateFormat.format(new Date(timestamp));
        } else {
            return $.dateFormat.format($.dateFormat.datetime(timestamp));
        }
    };

    var $t = $.dateFormat;

    $.extend($.dateFormat, {
            settings: {
                refreshMillis: 1000,
                todayFormat: '[сегодня в] HH:mm',
                yesterdayFormat: '[вчера в] HH:mm',
                thisYearFormat: 'D MMM в HH:mm',
                defaultFormat: 'D MMM YYYY в HH:mm'
            },
            format: function (date) {
                var $s = $t.settings;
                if (!moment(date).isValid()) {
                    return "неверное время";
                }

                if ($t.isInThisHour(date)) {
                    return moment(date).fromNow();
                }
                if ($t.isToday(date)) {
                    return moment(date).format($s.todayFormat);
                }
                if ($t.isYesterday(date)) {
                    return moment(date).format($s.yesterdayFormat);
                }
                if ($t.isInThisYear(date)) {
                    return moment(date).format($s.thisYearFormat);
                }
                return moment(date).format($s.defaultFormat);
            },
            isInThisHour: function (date) {
                return (new Date().getTime() - date.getTime()) < 1000 * 60 * 60;
            },
            isToday: function (date) {
                var today = new Date();
                return date.getFullYear() == today.getFullYear()
                    && date.getMonth() == today.getMonth()
                    && date.getDate() == today.getDate();
            },
            isYesterday: function (date) {
                var yday = new Date();
                yday.setHours(0);
                yday.setMinutes(0);
                yday.setDate(yday.getDate() - 1);

                if (!$t.isToday(date) && date.getTime() > yday.getTime()) {
                    return true;
                }

                return false;
            },
            isInThisYear: function (date) {
                return date.getFullYear() == new Date().getFullYear()
            },
            datetime: function (elem) {
                var iso8601 = $t.isTime(elem) ? $(elem).attr("datetime") : $(elem).attr("title");
                return $t.parse(iso8601);
            },
            isTime: function (elem) {
                return $(elem).get(0).tagName.toLowerCase() === "time";
            },
            parse: function (iso8601) {
                var s = $.trim(iso8601);
                s = s.replace(/\.\d+/, "");
                s = s.replace(/-/, "/").replace(/-/, "/");
                s = s.replace(/T/, " ").replace(/Z/, " UTC");
                s = s.replace(/([\+\-]\d\d)\:?(\d\d)/, " $1$2");
                return new Date(s);
            }
        }
    );

    var refreshElements = [];
    var intervalId = null;

    function startTimer(){

        for(var i = 0; i < refreshElements.length; i++){
            var el = refreshElements[i];
            var data = el.data("dateFormat");
            el.text($t(data.datetime));
        }
    };

    $.fn.dateFormat = function (action, options) {
        this.each(function () {
            var el = $(this);

            var data = el.data("dateFormat");

            if (!data) {
                data = { datetime: $t.datetime(el) };
                el.data("dateFormat", data);

                if (!$t.isTime(el)) {
                    el.attr('datetime', $(el).attr("title"));
                }

                if ($t.isInThisHour(data.datetime)){
                    refreshElements.push(el);
                }
            }

            var data = el.data("dateFormat");

            el.text($t(data.datetime));

            if ($t.settings.refreshMillis > 0) {
                if ($t.isInThisHour(data.datetime)) {
                    if(!intervalId){
                        intervalId = setInterval(startTimer, $t.settings.refreshMillis);
                    }
                }
            }
        });
    };
})(jQuery, moment);