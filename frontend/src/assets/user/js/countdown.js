(function ($) {
    function pad(n) {
        return (n < 10) ? ("0" + n) : n;
    }

    $.fn.showclock = function () {

        /*var currentDate=new Date();
        var fieldDate=$(this).data('date').split('-');
        var futureDate=new Date(fieldDate[0],fieldDate[1]-1,fieldDate[2]);
        var seconds=futureDate.getTime() / 1000 - currentDate.getTime() / 1000;*/
        var sdate = $(this).attr('data-start');
        var send = $(this).attr('data-end');

        var sold_amount = $('#data-token-sold').attr('data-sold-amount');
        var round_amount = $('#data-token-sold').attr('data-round-amount');
        if (typeof isCountryAllowedToBuyCoin !== "undefined" && isCountryAllowedToBuyCoin === false) {
            $('.countdown-card').removeClass('opening');
            $('h3.opening-time').html("This service is not available in your country.");
            $('.ico-notifications').removeClass('show');
            $('.progress-bar-wrapper').hide();
            $(this).hide();
        }
        if (sdate <= 0 && send > 0 || sdate === send) {
            if (typeof isCountryAllowedToBuyCoin !== "undefined" && isCountryAllowedToBuyCoin === true) {
                $('#btn-buy-coin').removeAttr('disabled');
            }
        } else {
            $('#btn-buy-coin').attr('disabled','');
        }

        if (send <= 0) {
            $('#btn-buy-coin').attr('disabled','');
            return;
        }

        if(parseInt(sold_amount) == parseInt(round_amount)) {
            $('#btn-buy-coin').attr('disabled','');
        }

        var isIcoOpening = false;
        var seconds = sdate;
        if (sdate <= 0 && send > 0 || sdate === send) {
            seconds = send;
            isIcoOpening = true;
        }
        --sdate;
        --send;
        if (isIcoOpening === true) {
            $(this).attr('data-start', send);
        } else {
            $(this).attr('data-start', sdate);
        }
        $(this).attr('data-end', send);
        if (seconds <= 0 || isNaN(seconds)) {
            this.hide();
            return this;
        }
        var days = Math.floor(seconds / 86400);
        seconds = seconds % 86400;

        var hours = Math.floor(seconds / 3600);
        seconds = seconds % 3600;
        hours = parseInt(hours);

        var minutes = Math.floor(seconds / 60);
        seconds = Math.floor(seconds % 60);

        var html = "";

        if (days != 0) {
            html += "<div class='countdown-container days'>";
            html += "<span class='countdown-heading days-top'>Days</span>";
            html += "<span class='countdown-value days-bottom'>" + pad(days) + "</span>";
            html += "</div>";
        }
        if (hours >= 0) {
            html += "<div class='countdown-container hours'>";
            html += "<span class='countdown-heading hours-top'>Hours</span>";
            html += "<span class='countdown-value hours-bottom'>" + pad(hours) + "</span>";
            html += "</div>";
        }
        if (minutes >= 0) {
            html += "<div class='countdown-container minutes'>";
            html += "<span class='countdown-heading minutes-top'>Minutes</span>";
            html += "<span class='countdown-value minutes-bottom'>" + pad(minutes) + "</span>";
            html += "</div>";
        }
        if (seconds >= 0) {
            html += "<div class='countdown-container seconds'>";
            html += "<span class='countdown-heading seconds-top'>Seconds</span>";
            html += "<span class='countdown-value seconds-bottom'>" + pad(seconds) + "</span>";
            html += "</div>";
        }

        if (typeof isCountryAllowedToBuyCoin !== "undefined" && isCountryAllowedToBuyCoin === true) {
            if (isIcoOpening === false) {
                $('.countdown-card').removeClass('opening');
                $('h3.opening-time').html("JGN token sale closed. <span>Please wait for the next open in</span>");
                $('.ico-notifications').removeClass('show');
            } else {
                $('.countdown-card').addClass('opening');
                $('h3.opening-time').html("ICO stage 4th round is opened. Buy JGN price $1.15 now!<span>The round will be closed in</span>");
                $('.ico-notifications').addClass('show');
            }
        }

        this.html(html);
    };

    $.fn.countdown = function () {
        var el = $(this);
        el.showclock();
        setInterval(function () {
            el.showclock();
        }, 1000);
        setInterval(function () {
            $.get('/ajax/day-sold-tokens', {}, function (res) {
                var el = $('#data-token-sold');
                el.attr('data-sold-amount', res.soldAmount).css({'width': (res.soldAmount / parseInt(el.attr('data-round-amount')))*100 + '%' });
                $('#progress-tooltip').attr('title', res.soldDisplayAmount).tooltip('fixTitle');
                // $('#progress-tooltip').attr('data-original-title', res.soldDisplayAmount);
                // $('#progress-tooltip').tooltip('remove');
                // $('#progress-tooltip').tooltip();
            })
        }, 15000)
    }
}(jQuery));

jQuery(document).ready(function () {
    if (jQuery(".countdown").length > 0)
        jQuery(".countdown").countdown();
});