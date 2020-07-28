$(document).ready(function(){

    $('.nav-resize').click(function(){
        if(!$(this).is('.active')) {
            if($('.rightbar').is('.active')) {
                $('.action-rightbar, .rightbar').removeClass('active');
            }
            $(this).addClass('active');
            $('.leftbar, .fade, body').addClass('active');
        } else {
            $('.leftbar, .fade, body').removeClass('active');
            $(this).removeClass('active');
        }
        return false;
    });

    $(document).on('click','[rel=popup]', function() {
        showPopup($(this).attr('href'));
        return false;
    });

    $('[rel=close]').click(function(){
        closePopup();
    });

    $('.overlay').click(function(e) {
        var target = e.srcElement || e.target;
        if(!target.className.search('overlay')) {
            closePopup();
        }
    });

    $(document).keyup(function(e) {
        if(e.keyCode === 27) {
            if($('.popup').is('.active')) {
                closePopup();
            }
            if($('.select').is('.active')) {
                $('.select').removeClass('active');
            }
            if($('.nav').is('.active')) {
                $('.nav').removeClass('active');
            }
        }
    });

    $(document).on('click touchend', 'body', function(e){
        var target = e.srcElement || e.target;
        if($('.nav').is('.active')) {
            $('.nav').removeClass('active');
        }
        if($('.select').is('.active')) {
            $('.select').removeClass('active');
        }
    });


    $('.leftbar .block div.item').click(function(e) {
        if($(this).is('.active')) {
            $('.leftbar .block .sublist').height(0);
            $(this).removeClass('active');
        } else {
            $('.leftbar .block .sublist').height(0);
            $('.leftbar .block .item').removeClass('active');

            $(this).find('.sublist').height($(this).find('.height').height());
            $(this).addClass('active');
        }
        setTimeout(function(){
            initLeftBarScroll();
        },450);
        return false;
    });

    $('.leftbar .block .subitem').click(function(e) {

        return false;
    });



    $(window).resize(function(){
        initLeftBarScroll();
        initRightBarScroll();
        initLeftBarScrollSecond();
    }).resize();

    if($('.slider').length) {
        var slider = $('.slider .list').lightSlider({
            item: 1,
            pager: false,
            auto: true,
            pause: 4000,
            controls: false
        });
        $('.slider .next').on('click',function(){
            slider.goToNextSlide();
            return false;
        });
        $('.slider .prev').on('click',function(){
            slider.goToPrevSlide();
            return false;
        });
    }


    $('.tabs .tab').on('click',function(){
        if(!$(this).is('.active')) {
            $('.tabs .tab[data-type="'+$(this).attr('data-type')+'"], .tab-content[data-type="'+$(this).attr('data-type')+'"]').removeClass('active');
            $(this).addClass('active');
            $('.tab-content[data-type="'+$(this).attr('data-type')+'"]'+$(this).attr('href')).addClass('active');
        }
        return false;
    });

    if($('.input-tel').length) {
        var input = document.querySelector('.input-tel');
        window.intlTelInput(input, {
            utilsScript: 'template/js/utils.js',
            onlyCountries: ["al", "ad", "at", "by", "be", "ba", "bg", "hr", "cz", "dk",
                "ee", "fo", "fi", "fr", "de", "gi", "gr", "va", "hu", "is", "ie", "it", "lv",
                "li", "lt", "lu", "mk", "mt", "md", "mc", "me", "nl", "no", "pl", "pt", "ro",
                "ru", "sm", "rs", "sk", "si", "es", "se", "ch", "ua", "gb"],
            autoHideDialCode: false,
            autoPlaceholder: false,
            initialCountry: 'ru',
            separateDialCode: true,
            nationalMode: true
        });
        initTelScroll();
    }


    $(document).on('click touchend', '.select .current', function(e){
        if($(this).parent().is('.active')) {
            $(this).parent().removeClass('active');
        } else {
            if($('.select').is('.active')) {
                $('.select').removeClass('active');
            }

            $(this).parent().addClass('active');
        }
        return false;
    });

    $(document).on('click touchend', '.select .dropdown .select-item', function(e){
        $(this).parent().parent().find('.current').attr('data-value',$(this).attr('data-value'));
        $(this).parent().parent().find('.current').html($(this).html());
        $('.select').removeClass('active');
        return false;
    });

    $(document).on('click touchend', '.action-rightbar', function(e){
        if(!$(this).is('.active')) {
            if($('.leftbar').is('.active')) {
                $('.action-leftbar, .leftbar').removeClass('active');
            }
            $(this).addClass('active');
            $('.rightbar, .fade, body').addClass('active');
        } else {
            $('.rightbar, .fade, body').removeClass('active');
            $(this).removeClass('active');
        }
        return false;
    });

    $(document).on('click touchend', '.fade', function(e){
        $('.leftbar, .fade, .rightbar, .action-leftbar, .action-rightbar, body').removeClass('active');
        return false;
    });

    $('.result-item .heading').on('click touchend',function(){
        if($(this).parent().is('.active')) {
            $(this).parent().removeClass('active');
        } else {
            $('.result-item').removeClass('active');
            $(this).parent().addClass('active');
        }
        return false;
    });

    if(window.location.hash == '#scrollToContainer') {
        if($(window).width() < 1241) {
            $('html,body').animate({scrollTop: $('.content .container').offset().top-122},500);
            if(window.history.pushState) {
                window.history.pushState('', '/', window.location.pathname)
            } else {
                window.location.hash = '';
            }
        }
    }

});

function initLeftBarScroll() {
    if($('.leftbar .scroll:not(.second)').length) {
        var sHeight = $(window).height()-162;
        if($('.block-hot').length) {
            sHeight = $(window).height()-152 - $('.block-hot').height()-24;
        }

        if($(window).width() < 1501 && $(window).width() > 1240) {
            sHeight = sHeight+30;
        }

        if($(window).width() < 1241) {
            sHeight = sHeight+50;
        }

        $('.leftbar .scroll:not(.second)').slimScroll({
            height: sHeight,
            allowPageScroll: false,
            size: '4px',
            color: '#4a9aeb',
            opacity: 1,
            railColor: '#18212b',
            railOpacity: 1,
            alwaysVisible: true,
            railVisible: true,
            start: 'top'
        });
    }
}

function initLeftBarScrollSecond() {
    if($('.leftbar .scroll').length) {
        var sHeight = $(window).height()-162;
        if($('.block-hot').length) {
            sHeight = $(window).height()-152 - $('.block-hot').height()-24;
        }

        if($(window).width() < 1501 && $(window).width() > 1240) {
            sHeight = sHeight+30;
        }

        if($(window).width() < 1241) {
            sHeight = sHeight+50;
        }

        if($(window).width() < 480) {
            sHeight = sHeight+50;
        }

        if($(window).width() < 480 && $(window).height() > 680) {
            sHeight = sHeight+70;
        }

        $('.leftbar .scroll').slimScroll({
            height: sHeight,
            allowPageScroll: false,
            size: '4px',
            color: '#4a9aeb',
            opacity: 1,
            railColor: '#18212b',
            railOpacity: 1,
            alwaysVisible: false,
            railVisible: true,
            start: 'top'
        });
    }
}

function initRightBarScroll() {
    if($('.rightbar .scroll').length) {
        var sHeight = $(window).height()-120;

        if($(window).width() < 1501 && $(window).width() > 1240) {
            sHeight = sHeight+30;
        }

        if($(window).width() < 1241) {
            sHeight = sHeight+50;
        }

        $('.rightbar .scroll').slimScroll({
            height: sHeight,
            allowPageScroll: false,
            size: '4px',
            color: '#4a9aeb',
            opacity: 1,
            railColor: '#18212b',
            railOpacity: 1,
            alwaysVisible: false,
            railVisible: true,
            start: 'top'
        });
    }
}

function initTelScroll() {
    $('.intl-tel-input .country-list').slimScroll({
        height: 200,
        allowPageScroll: false,
        size: '4px',
        color: '#4a9aeb',
        opacity: 1,
        railColor: '#18212b',
        railOpacity: 1,
        alwaysVisible: true,
        railVisible: true,
        start: 'top'
    });
}

function showPopup(el) {
    if($('.popup').is('.active')) {
        $('.popup').removeClass('active');
    }
    if($(document).height() > $(window).height() && navigator.platform.indexOf('Mac') === -1) {
        $('body').addClass('active');
    }
    $('.overlay, .popup'+el).addClass('active');
}

function closePopup() {
    if($(document).find("div.popup.active").hasClass("notclosed")) {
        window.location.href = "/?do=history-cash&cashout=true";
    } else {
        $('body, .overlay, .popup').removeClass('active');
    }
}


/*  Roulette */
(function($) {
        var Roulette = function(options) {
            var defaultSettings = {
                maxPlayCount: null,
                speed: 10,
                stopImageNumber: null,
                rollCount: 3,
                duration: 3,
                stopCallback: function() {},
                startCallback: function() {},
                slowDownCallback: function() {}
            };
            var defaultProperty = {
                playCount: 0,
                $rouletteTarget: null,
                imageCount: null,
                $images: null,
                originalStopImageNumber: null,
                totalHeight: null,
                topPosition: 0,
                maxDistance: null,
                slowDownStartDistance: null,
                isRunUp: true,
                isSlowdown: false,
                isStop: false,
                distance: 0,
                runUpDistance: null,
                isIE: navigator.userAgent.toLowerCase().indexOf("msie") > -1
            };
            var p = $.extend({}, defaultSettings, options, defaultProperty);
            var reset = function() {
                p.maxDistance = defaultProperty.maxDistance;
                p.slowDownStartDistance = defaultProperty.slowDownStartDistance;
                p.distance = defaultProperty.distance;
                p.isRunUp = defaultProperty.isRunUp;
                p.isSlowdown = defaultProperty.isSlowdown;
                p.isStop = defaultProperty.isStop;
                p.topPosition = defaultProperty.topPosition
            };
            var slowDownSetup = function() {
                if (p.isSlowdown) {
                    return
                }
                p.slowDownCallback();
                p.isSlowdown = true;
                p.slowDownStartDistance = p.distance;
                p.maxDistance = p.distance + 2 * p.totalHeight;
                p.maxDistance += p.imageHeight - p.topPosition % p.imageHeight;
                if (p.stopImageNumber != null) {
                    p.maxDistance += (p.totalHeight - p.maxDistance % p.totalHeight + p.stopImageNumber * p.imageHeight) % p.totalHeight
                }
            };
            var roll = function() {
                var speed_ = p.speed;
                if (p.isRunUp) {
                    if (p.distance <= p.runUpDistance) {
                        var rate_ = ~~(p.distance / p.runUpDistance * p.speed);
                        speed_ = rate_ + 1
                    } else {
                        p.isRunUp = false
                    }
                } else if (p.isSlowdown) {
                    var rate_ = ~~((p.maxDistance - p.distance) / (p.maxDistance - p.slowDownStartDistance) * p.speed);
                    speed_ = rate_ + 1
                }
                if (p.maxDistance && p.distance >= p.maxDistance) {
                    p.isStop = true;
                    reset();
                    p.stopCallback(p.$rouletteTarget.find("img").eq(p.stopImageNumber));
                    return
                }
                p.distance += speed_;
                p.topPosition += speed_;
                if (p.topPosition >= p.totalHeight) {
                    p.topPosition = p.topPosition - p.totalHeight
                }
                if (p.isIE) {
                    p.$rouletteTarget.css("top", "-" + p.topPosition + "px")
                } else {
                    p.$rouletteTarget.css("transform", "translate(0px, -" + p.topPosition + "px)")
                }
                setTimeout(roll, 1)
            };
            var init = function($roulette) {
                $roulette.css({
                    overflow: "hidden"
                });
                defaultProperty.originalStopImageNumber = p.stopImageNumber;
                if (!p.$images) {
                    p.$images = $roulette.find("img").remove();
                    p.imageCount = p.$images.length;
                    p.$images.eq(0).bind("load", function() {
                        p.imageHeight = $(this).height();
                        $roulette.css({
                            height: p.imageHeight + "px"
                        });
                        p.totalHeight = p.imageCount * p.imageHeight;
                        p.runUpDistance = 2 * p.imageHeight
                    }).each(function() {
                        if (this.complete || this.complete === undefined) {
                            var src = this.src;
                            this.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
                            this.src = src
                        }
                    })
                }
                $roulette.find("div").remove();
                p.$images.css({
                    display: "block"
                });
                p.$rouletteTarget = $("<div>").css({
                    position: "relative",
                    top: "0"
                }).attr("class", "roulette-inner");
                $roulette.append(p.$rouletteTarget);
                p.$rouletteTarget.append(p.$images);
                p.$rouletteTarget.append(p.$images.eq(0).clone());
                $roulette.show()
            };
            var start = function() {
                p.playCount++;
                if (p.maxPlayCount && p.playCount > p.maxPlayCount) {
                    return
                }
                p.stopImageNumber = $.isNumeric(defaultProperty.originalStopImageNumber) && Number(defaultProperty.originalStopImageNumber) >= 0 ? Number(defaultProperty.originalStopImageNumber) : Math.floor(Math.random() * p.imageCount);
                p.startCallback();
                roll();
                setTimeout(function() {
                    slowDownSetup()
                }, p.duration * 1e3)
            };
            var stop = function(option) {
                if (!p.isSlowdown) {
                    if (option) {
                        var stopImageNumber = Number(option.stopImageNumber);
                        if (0 <= stopImageNumber && stopImageNumber <= p.imageCount - 1) {
                            p.stopImageNumber = option.stopImageNumber
                        }
                    }
                    slowDownSetup()
                }
            };
            var option = function(options) {
                p = $.extend(p, options);
                p.speed = Number(p.speed);
                p.duration = Number(p.duration);
                p.duration = p.duration > 1 ? p.duration - 1 : 1;
                defaultProperty.originalStopImageNumber = options.stopImageNumber
            };
            var ret = {
                start: start,
                stop: stop,
                init: init,
                option: option
            };
            return ret
        };
        var pluginName = "roulette";
        $.fn[pluginName] = function(method, options) {
            return this.each(function() {
                var self = $(this);
                var roulette = self.data("plugin_" + pluginName);
                if (roulette) {
                    if (roulette[method]) {
                        roulette[method](options)
                    } else {
                        console && console.error("Method " + method + " does not exist on jQuery.roulette")
                    }
                } else {
                    roulette = new Roulette(method);
                    roulette.init(self, method);
                    $(this).data("plugin_" + pluginName, roulette)
                }
            })
        }
    }
)(jQuery);

/* animateNumber */
(function(d) {
        var q = function(b) {
            return b.split("").reverse().join("")
        }
            , m = {
            numberStep: function(b, a) {
                var e = Math.floor(b);
                d(a.elem).text(e)
            }
        }
            , h = function(b) {
            var a = b.elem;
            a.nodeType && a.parentNode && (a = a._animateNumberSetter,
            a || (a = m.numberStep),
                a(b.now, b))
        };
        d.Tween && d.Tween.propHooks ? d.Tween.propHooks.number = {
            set: h
        } : d.fx.step.number = h;
        d.animateNumber = {
            numberStepFactories: {
                append: function(b) {
                    return function(a, e) {
                        var g = Math.floor(a);
                        d(e.elem).prop("number", a).text(g + b)
                    }
                },
                separator: function(b, a, e) {
                    b = b || " ";
                    a = a || 3;
                    e = e || "";
                    return function(g, k) {
                        var c = Math.floor(g).toString()
                            , t = d(k.elem);
                        if (c.length > a) {
                            for (var f = c, l = a, m = f.split("").reverse(), c = [], n, r, p, s = 0, h = Math.ceil(f.length / l); s < h; s++) {
                                n = "";
                                for (p = 0; p < l; p++) {
                                    r = s * l + p;
                                    if (r === f.length)
                                        break;
                                    n += m[r]
                                }
                                c.push(n)
                            }
                            f = c.length - 1;
                            l = q(c[f]);
                            c[f] = q(parseInt(l, 10).toString());
                            c = c.join(b);
                            c = q(c)
                        }
                        t.prop("number", g).text(c + e)
                    }
                }
            }
        };
        d.fn.animateNumber = function() {
            for (var b = arguments[0], a = d.extend({}, m, b), e = d(this), g = [a], k = 1, c = arguments.length; k < c; k++)
                g.push(arguments[k]);
            if (b.numberStep) {
                var h = this.each(function() {
                    this._animateNumberSetter = b.numberStep
                })
                    , f = a.complete;
                a.complete = function() {
                    h.each(function() {
                        delete this._animateNumberSetter
                    });
                    f && f.apply(this, arguments)
                }
            }
            return e.animate.apply(e, g)
        }
    }
)(jQuery);

function cleanWinAnimation() {

    //$("#audio-win").animate({volume: 0.0}, 300, function(){
    // var audioWin = document.getElementById("audio-win-" + window.win_sound);
    // audioWin.currentTime = 0;
    // audioWin.pause();
    //});

    //$("#audio-spin").animate({volume: 0.0}, 300, function(){
    // var audioSpin = document.getElementById("audio-spin");
    // audioSpin.currentTime = 0;
    // audioSpin.pause();
    //});

    $(".spin-won").fadeOut(300);
    $(".history-cases").fadeIn(300);
    $(".case-page-title").fadeIn(300);
}

function winAnimation(type) {

    window.win_sound = Math.round(Math.random() * 1);

    if (type == 2) {
        $(".spin-won h4").fadeIn(0);
    } else {
        $(".spin-won h4").fadeOut(0);
    }

    $("#audio-spin").animate({
        volume: 0.0
    }, 300, function() {
        $("#audio-spin").trigger('stop');
        $("#audio-win-" + window.win_sound).trigger('play');
        $("#audio-win-" + window.win_sound).animate({
            volume: 1.0
        }, 1000);
    });

    $(".spin-won").fadeIn(300);
    $(".history-cases").fadeOut(0);
    $(".case-page-title").fadeOut(0);
}

var roundOptions = new Array;
var rouletteObject = new Array;

function spinbox(gameId, button, count) {
    var gameButton = $(button);
    var gamePrice = parseFloat($("#spin-amount").text());
    var gameButtonText = $(button).html();
    var gameLoader = $("#game-" + gameId + " .loading");
    var otherButtons = $(".three .btn");
    var gameChance = window.spin_chance;

    gameButton.text("Открываем кейс...");
    gameButton.attr("disabled", "disabled");

    // $("#audio-win-" + window.win_sound).animate({
    //     volume: 0.0
    // }, 0);
    // $("#audio-spin").animate({
    //     volume: 0.0
    // }, 0);

    $.ajax({
        url: '/engine/ajax/case.php',
        data: {
            gameId: gameId
        }
    }).done(function (response) {
        var resultData = $.parseJSON(response);


        var showResult = resultData.data;

        if (resultData.status == 1) {

            var giftImg = 0;
            $.each($('.roulette img'), function() {
                var g = parseInt($(this).attr("id").split('gift-id-')[1]);
                giftImg++;
                if (g == showResult['gift']) {
                    showResult.result = giftImg - 1;

                }
            });

            roundOptions[gameId] = {
                speed: 24,
                duration: 1,
                stopImageNumber: showResult.result,
                startCallback: function() {

                    // UpdateBalance(-1 * gamePrice);

                    // var socket = io.connect(':2020');

                    // socket.emit('last gift set');

                },
                stopCallback: function() {

                    var startNewGame = setTimeout(function() {

                        gameButton.html(gameButtonText);
                        gameButton.removeAttr("disabled");

                        // UpdateBalance(showResult['win_sum']);

                        /* win info */
                        $("#spin-win-name").html(showResult['text']);
                        $("#spin-win-icon").attr("src", showResult['photo']);

                        winAnimation(showResult['type']);

                    }, 1000);
                }
            }

            if (rouletteObject[gameId] == undefined || rouletteObject[gameId] == 'undefined') {
                rouletteObject[gameId] = $(".roulette").roulette(roundOptions[gameId]);
            } else {
                rouletteObject[gameId].roulette('option', roundOptions[gameId]);
            }
            rouletteObject[gameId].roulette('start');

            // $("#audio-spin").trigger('play');
            // $("#audio-spin").animate({
            //     volume: 1.0
            // }, 2000);

            if (resultData.data && resultData.data.balance) {
                setTimeout(function () {
                    $('.balances .item .desc.c-green').html(resultData.data.balance)
                }, 7000)
            }
        } else {
            showPopup("#error");
            if (resultData.error) {
                $('#error_text').html(resultData.error);
            }
            gameButton.html(gameButtonText);
            gameButton.removeAttr("disabled");
        }
    }).fail(function () {
        showPopup("#error");
    });
}