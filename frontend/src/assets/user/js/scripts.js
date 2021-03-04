$( document ).ready(function() {

    // Write your custom Javascript codes here...
    $('#buy-amount').click(function(){
        //var max_buy_per_day = $('#max-buy-per-day-for-user').attr('data-value');
        var jgn_btc = $('#jgn-btc').attr('data-value');
        var jgn_eth = $('#jgn-eth').attr('data-value');
        var jgn_bch = $('#jgn-bch').attr('data-value');
        var btc_coin = $(this).attr('data-btc');
        var eth_coin = $(this).attr('data-eth');
        var bch_coin = $(this).attr('data-bch');

        var amount = Math.floor(btc_coin / jgn_btc) + Math.floor(eth_coin / jgn_eth) + Math.floor(bch_coin / jgn_bch);
        /*if(amount > max_buy_per_day) {
            amount = max_buy_per_day;
        }*/

        $('#buy_amount').val(Math.floor(amount));
        $('#buy_amount').change();
        
        exchangeBTCAndUSD();

    });

    $('#buy_amount').change(function () {
        var amount = $(this).val();
        amount = Math.floor(amount);
        $(this).val(amount);
        exchangeBTCAndUSD();
    });

    function exchangeBTCAndUSD() {
        var amount = $('#buy_amount').val();

        var btc_coin = $('#buy-amount').attr('data-btc');
        var eth_coin = $('#buy-amount').attr('data-eth');
        var bch_coin = $('#buy-amount').attr('data-bch');

        var rate_btc = $('#rate-btc').attr('data-value');
        var rate_eth = $('#rate-eth').attr('data-value');
        var rate_bch = $('#rate-bch').attr('data-value');
        var rate_usd = $('#rate-usd').attr('data-value');

        var jgn_usd = amount * rate_usd;
        var total_usd = jgn_usd;
        var btc_usd = btc_coin * rate_btc;
        var eth_usd = eth_coin * rate_eth;
        var bch_usd = bch_coin * rate_bch;

        var btc_amount = 0;
        var eth_amount = 0;
        var bch_amount = 0;

        if(jgn_usd > btc_usd) {
            btc_amount = btc_coin;
            jgn_usd -= btc_usd;
        }else {
            btc_amount = jgn_usd / rate_btc;
            jgn_usd = 0;
        }

        if(jgn_usd > eth_usd) {
            eth_amount = eth_coin;
            jgn_usd -= eth_usd;
        }else {
            eth_amount = jgn_usd / rate_eth;
            jgn_usd = 0;
        }

        if(jgn_usd > bch_usd) {
            bch_amount = bch_coin;
            jgn_usd -= bch_usd;
        }else {
            bch_amount = jgn_usd / rate_bch;
            jgn_usd = 0;
        }

        $('#btc_amount').val(parseFloat(btc_amount).toFixed(8) + ' BTC');
        $('#eth_amount').val(parseFloat(eth_amount).toFixed(8) + ' ETH');
        $('#bch_amount').val(parseFloat(bch_amount).toFixed(8) + ' BCH');
        $('#usd_amount').val(parseFloat(total_usd).toFixed(2) + ' USD');

        $('#btc_amount').change();
        $('#eth_amount').change();
        $('#bch_amount').change();
        $('#usd_amount').change();
    }

    $('.button-copy').click(function(){
        event.preventDefault();
        var dataId = $(this).attr('data-value-id');
        console.log($('#'+dataId).val());
        if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
            var $input = $('#'+dataId);
            $input.val();
            var el = $input.get(0);
            var editable = el.contentEditable;
            var readOnly = el.readOnly;
            el.contentEditable = true;
            el.readOnly = false;
            var range = document.createRange();
            range.selectNodeContents(el);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
            el.setSelectionRange(0, 999999);
            el.contentEditable = editable;
            el.readOnly = readOnly;

            document.execCommand('copy');
            $input.blur();
        }
        else {

            copyTextToClipboard(document.getElementById(dataId));
        }
        $(this).html('Copied');
        setTimeout(function() {
            $('.button-copy').html('Copy');
        }, 3000);
    });

    function scaleCaptcha(elementWidth) {
        // Width of the reCAPTCHA element, in pixels
        var reCaptchaWidth = 304;
        // Get the containing element's width
        var containerWidth = $('.g-recaptcha-container').width();

        // Only scale the reCAPTCHA if it won't fit
        // inside the container
        if(reCaptchaWidth > containerWidth) {
            // Calculate the scale
            var captchaScale = containerWidth / reCaptchaWidth;
            // Apply the transformation
            $('.g-recaptcha').css({
                'transform':'scale('+captchaScale+')'
            });
        } else {
            $('.g-recaptcha-container').addClass('large-size');
        }
    }

    $(function() {

        // Initialize scaling
        scaleCaptcha();

        // Update scaling on window resize
        // Uses jQuery throttle plugin to limit strain on the browser
        // $(window).resize( $.throttle( 100, scaleCaptcha ) );

    });

    /**
    function mreload() {
        $.get('/index/scanbtc', {}, function (res) {
            console.log(res);
            $('#index-btc-amount').html(parseFloat(res.amount).toFixed(8));
            $('#buy-amount').attr('data-value', parseFloat(res.amount).toFixed(8));
            $('#buy-amount').html('Buy all ' + parseFloat(res.amount).toFixed(8));
        });

        setInterval(function () {
            $.get('/index/scanbtc', {}, function (res) {
                console.log(res);
                $('#index-btc-amount').html(parseFloat(res.amount).toFixed(8));
                $('#buy-amount').attr('data-value', parseFloat(res.amount).toFixed(8));
                $('#buy-amount').html('Buy all ' + parseFloat(res.amount).toFixed(8));
            });
        }, 30000)
    }*/

    //JQuery plugin:
    $.fn.textWidth = function(_text, _font){//get width of text with font.  usage: $("div").textWidth();
        var fakeEl = $('<span>').hide().appendTo(document.body).text(_text || this.val() || this.text()).css({font: _font || this.css('font'), whiteSpace: "pre"}),
            width = fakeEl.width();
        fakeEl.remove();
        return width;
    };

    $.fn.autoresize = function(options){//resizes elements based on content size.  usage: $('input').autoresize({padding:10,minWidth:0,maxWidth:100});
        options = $.extend({padding:10,minWidth:0,maxWidth:10000}, options||{});
        $(this).on('input', function() {
            $(this).css('width', Math.min(options.maxWidth,Math.max(options.minWidth,$(this).textWidth() + options.padding)));
        }).trigger('input');
        return this;
    };

    function getAdminBalance() {
        $.ajax({
            url: route_ajax_get_balance,
            type: 'post',
            dataType: 'json',
            success: function (data) {

                let balanceText = '<span class="rate_detail"><b>Balance:</b> ' +
                    'BTC %btc, ETH %eth, BCH %bch, (Total: $%total, Today: $%today)' +
                    '</span>';

                if (data.success) {
                    let zcoin_menu = $('#zcoin-menu');
                    let zcoin_sidebar = $('#zcoin-sidebar span.rate_detail');

                    balanceText = balanceText.replace('%btc', data.btc_balance);
                    balanceText = balanceText.replace('%eth', data.eth_balance);
                    balanceText = balanceText.replace('%bch', data.bch_balance);
                    balanceText = balanceText.replace('%total', data.usd_balance);
                    balanceText = balanceText.replace('%today', data.usd_today_amount);

                    zcoin_menu.append(balanceText);

                    balanceText = '<br><span class="rate_detail">' +
                        '<b>Balance:</b>' +
                        '<br>BTC ' + data.btc_balance +
                        '<br>ETH ' + data.eth_balance +
                        '<br>BCH ' + data.bch_balance +
                        '</span>';
                    zcoin_sidebar.last().after(balanceText);
                }
            }
        });
    }

    if (typeof route_ajax_get_balance !== "undefined") {
        getAdminBalance();
    }

    $('a#btc-withdraw').click(function() {
        $('form#send-btc-form').submit();
    });

    $('#btn-update-btc-deposit, .btn-refresh-transactions').click(function() {
        if ($(this).attr('disabled') === 'disabled') {
            return false;
        }
        $('#btn-update-btc-deposit, .btn-refresh-transactions').attr('disabled', 'disabled');
        $('.btn-refresh-transactions').html('Loading...');
        $('#transactions-deposit .dataTables_wrapper').html("<p class='center-align'>Loading...</p>")
        $.ajax({
            url: ajaxGetDepositTransactionsUrl,
            type: 'POST'
        }).done(function(data) {
            $('#transactions-deposit .dataTables_wrapper').html(data);
            $('.btn-refresh-transactions').html('Updated');
            $('#btn-update-btc-deposit').html('Updated');

            var btcAmount = $('#wallets-data').attr('data-btc-amount');
            var ethAmount = $('#wallets-data').attr('data-eth-amount');
            var bchAmount = $('#wallets-data').attr('data-bch-amount');
            var jgnAmount = $('#wallets-data').attr('data-jgn-amount');

            $('#index-btc-amount').html(btcAmount);
            $('#index-eth-amount').html(ethAmount);
            $('#index-bch-amount').html(bchAmount);
            $('#index-jgn-amount').html(jgnAmount);

            $('a#buy-amount').attr('data-btc', btcAmount);
            $('a#buy-amount').attr('data-eth', ethAmount);
            $('a#buy-amount').attr('data-bch', bchAmount);

            setTimeout(function() {
                $('.btn-refresh-transactions').html('Update Balance');
                $('#btn-update-btc-deposit').html('Update Transactions');
                $('#btn-update-btc-deposit, .btn-refresh-transactions').attr('disabled', false);
            }, 30000);
        });
    });

    // Trigger active Community Navigation tab
    $('.sidebar-wrapper .nav > li:last-child').trigger('click');

    // click reject
    $('.btn-reject-show').click(function () {
        var id = $(this).attr('data-id');
        var user = $(this).attr('data-user');
        var amount = $(this).attr('data-amount');
        var btc = $(this).attr('data-btc');
        var eth = $(this).attr('data-eth');
        var usd = $(this).attr('data-usd');

        $('#title-reject').html('You are trying to reject order #'+id);
        $('#show-username').html(user);
        $('#show-amount').html(amount);
        $('#show-btc').html(btc);
        $('#show-eth').html(eth);
        $('#show-usd').html(usd);

        $('#input-reject-id').val(id);
        $('#modal-reject').modal('show');
    });

    var listid = [];
    $('.btn-accept').click(function () {
        var bid = $(this).attr('data-id');
        var sended = true;
        for (var ii in listid) if (listid[ii] == bid) sended = false;
        if (sended == true) {
            //$(this).hide();
            if(typeof route_admin_buylog_accept == 'undefined') route_admin_buylog_accept = '';
            $.post(route_admin_buylog_accept, {id: bid}, function (res) {
                if (res.status == 1) {
                    $(this).parent().parent().hide();
                    showNotification(res.value, 'success');
                }
                else {
                    $(this).show();
                    showNotification(res.value, 'warning');
                }
            })
        }
    });

    var listid_reject = [];
    $('.btn-reject').click(function () {
        var bid = $('#input-reject-id').val();
        var sended = true;
        for (var ii in listid_reject) if (listid_reject[ii] == bid) sended = false;
        if (sended == true) {
            if(typeof route_admin_buylog_reject == 'undefined') route_admin_buylog_reject = '';
            $.post(route_admin_buylog_reject, {id: bid}, function (res) {
                $('#modal-reject').modal('hide');
                if (res.status == 1) {
                    showNotification(res.value, 'success');
                }
                else {
                    showNotification(res.value, 'warning');
                }
            })
        }
    });


    function showNotification (content, color){
        //var type = ['','info','success','warning','danger'];

        $.notify({
            icon: "ti-gift",
            message: content

        },{
            type: color,
            timer: 4000,
            placement: {
                from: 'top',
                align: 'right'
            }
        });
    }
    $('button.close-alert').click(function (event) {
        $(this).parent().hide(300);
    });

    $('[data-toggle="tooltip"]').tooltip();
});


function copyTextToClipboard(elem) {
    // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);

    // copy the selection
    var succeed;
    try {
        succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }

    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
}

