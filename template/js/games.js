var favouriteGames = (function (){
    function removeItem(elem) {
        var favId = $(elem).data('game-id');
        removeFromStorage(favId);
        $(elem).removeClass('fav-icon--active');
    }
    function addItem(elem) {
        var favId = $(elem).data('game-id');
        addToStorage(favId);
        $(elem).addClass('fav-icon--active');
    }
    function toggleFavItem(elem) {
        if ($(elem).hasClass('fav-icon--active')) {
            removeItem(elem);
        } else {
            addItem(elem);
        }
    }
    function addToStorage(id) {
        var favGames = getFromStorage();
        favGames.push(id);
        localStorage.setItem('fav-games', JSON.stringify(favGames));
    }
    function removeFromStorage(id) {
        var favGames = getFromStorage();
        var gameNumber;
        for (var i = 0; i < favGames.length; i++) {
            if (favGames[i] == id) {
                gameNumber = i;
                break;
            }
        }
        favGames.splice(gameNumber, 1);
        localStorage.setItem('fav-games', JSON.stringify(favGames));
    }
    function markGames(favGames) {
        for (var i = 0; i < favGames.length; i++) {
            $('.js-fav[data-game-id="' + favGames[i] + '"]').addClass('fav-icon--active');
        }
    }
    function getFromStorage() {
        return JSON.parse(localStorage.getItem('fav-games') || '[]');
    }
    return {
        init: function () {
            this.markFavGames();

            $(document).on('click', '.js-fav', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleFavItem(e.target);
            });
        },
        getDataFromStorage: function () {
            return getFromStorage();
        },
        markFavGames: function() {
            var favGames = getFromStorage();
            if (favGames.length) {
                markGames(favGames);
            }
        }
    }
})();

$(function() {
    favouriteGames.init();
    gameMenu.init();

    $('.js-navbar-btn').on('click', function (e) {
        e.preventDefault();
        if ($('.games-menu-panel').hasClass('games-menu-panel--active')) {
            $('.games-menu-panel').removeClass('games-menu-panel--active')
        } else {
            $('.games-menu-panel').addClass('games-menu-panel--active')
        }
    });

    $('.games-menu-panel .js-menu-link').on('click', function (e) {
        e.preventDefault();
        $('.games-menu-panel').removeClass('games-menu-panel--active')
    });
});

var gameMenu = (function (){
    var games = {};
    function getData(link, href, params) {
        var url = link || '/?do=games';

        var data = {};
        var param = params || '';
        if (param == 'fav') {
            var favGames = favouriteGames.getDataFromStorage();
            data.array = favGames;

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'JSON'
            }).done(function(data) {
                changeUrl(href);
                renderGames(data);
            });
        } else {
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'JSON'
            }).done(function(data) {
                changeUrl(href);
                renderGames(data);
            });
        }
    }
    function changeUrl(url) {
        window.history.pushState('Object', 'Title', url);
    }
    function renderGames(favGames) {
        $('.container .inner').html('');
        for (var i = 0; i < favGames.length; i++) {
            renderGame(favGames[i]);
        }
        favouriteGames.markFavGames();
    }
    function renderGame(data) {
        var isLogged = $('.mini-profile').length;

        var icon = '<span class="fav-icon js-fav" data-game-id="' + data.uuid + '">\n' +
            '         <svg viewBox="0 0 512 512" width="18" height="18" xmlns="http://www.w3.org/2000/svg">\n' +
            '            <path class="heart" fill="#000" d="m511.808594 136.683594c-3.820313-76.011719-66.859375-136.683594-143.808594-136.683594-43.925781 0-84.777344 19.796875-112 53.632812-27.242188-33.835937-68.097656-53.632812-112-53.632812-76.949219 0-140.011719 60.671875-143.808594 136.683594-.128906.660156-.191406 1.300781-.191406 1.984375v5.332031c0 .683594.0625 1.324219.191406 1.960938 6.164063 126.1875 168.382813 315.523437 250.324219 364.523437 1.6875 1.023437 3.585937 1.515625 5.484375 1.515625s3.796875-.492188 5.484375-1.515625c81.960937-49 244.160156-238.335937 250.324219-364.523437.128906-.636719.191406-1.277344.191406-1.960938v-5.332031c0-.683594-.0625-1.324219-.191406-1.984375zm0 0"/>\n' +
            '             <path fill="#393f47" d="m256 512c-1.898438 0-3.796875-.492188-5.484375-1.515625-81.960937-49-244.160156-238.335937-250.324219-364.523437-.128906-.636719-.191406-1.277344-.191406-1.960938v-5.332031c0-.683594.0625-1.324219.191406-1.984375 3.796875-76.011719 66.859375-136.683594 143.808594-136.683594 43.902344 0 84.757812 19.796875 112 53.632812 27.222656-33.835937 68.074219-53.632812 112-53.632812 76.949219 0 139.988281 60.671875 143.808594 136.683594.128906.660156.191406 1.300781.191406 1.984375v5.332031c0 .683594-.0625 1.324219-.191406 1.960938-6.164063 126.1875-168.382813 315.523437-250.324219 364.523437-1.6875 1.023437-3.585937 1.515625-5.484375 1.515625zm-234.644531-370.667969c2.367187 112.597657 152.703125 294.421875 234.644531 347.4375 81.941406-53.015625 232.277344-234.839843 234.644531-347.4375-1.40625-66.410156-55.890625-120-122.644531-120-41.769531 0-80.296875 21.035157-103.039062 56.277344-3.925782 6.082031-13.996094 6.101563-17.921876 0-22.761718-35.242187-61.289062-56.277344-103.039062-56.277344-66.753906 0-121.238281 53.589844-122.644531 120zm0 0"/>\n' +
            '         </svg>\n' +
            '         </span>\n';

        var template = '<div class="case-grid case-grid--game show" data-case-type="1">\n' +
            '    <div class="case case--game">\n' +
            '<div class="game-case">\n' +
            '            <span class="game-case__mask">\n' +
            '                <img class="game-case__img" src="' + data.img + '" alt="">\n' +
            '            </span>\n' +
            '            <span class="game-case__info">\n' +
            (isLogged ? icon : '') +
            '                <h5 class="game-case__title">' + data.title + '</h5>\n' +
            '                <ul class="game-case__info-masks">\n' +
            '                    <li></li>\n' +
            '                    <li></li>\n' +
            '                    <li></li>\n' +
            '                    <li></li>\n' +
            '                </ul>\n' +
            '            </span>\n' +
            (isLogged ? ('<a class="game-case__btn" href="/?do=game&amp;run=' + data.uuid + '">play</a>\n') : ('<a class="game-case__btn" href="#signin" rel="popup">play</a>\n')) +
            '            <a class="game-case__demo" href="/?do=game&amp;run=' + data.uuid + '&demo=1">demo</a>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</div>';

        $('.containerpadding').css('display', 'block');
        $('.container .inner').removeClass('inner--game');
        $('.container .inner').append(template);
    }
    function searchGamesByText(text) {
        if (typeof text != "undefined" && text != '') {
            $.ajax({
                type: 'POST',
                url: '/?do=games&type=lottery&api=1&search=' + text,
                dataType: 'JSON'
            }).done(function(data) {
                renderGames(data);
            });
        }
    }
    return {
        init: function () {
            $('.js-menu-link').on('click', function (e) {
                e.preventDefault();
                $('.js-navbar-btn').find('.games-menu-panel__navbar-text').text($(e.target).find('.desc').text());
                var params = $(e.target).data('menu');
                var link = $(e.target).data('href');
                var href = $(e.target).prop('href');
                getData(link, href, params);
                $('.item--active').removeClass('item--active');
                $(e.target).addClass('item--active');
            });

            $('.js-menu-input').on('input', function (e) {
                var text = $(e.target).val();
                searchGamesByText(text);
            });

            $('.js-menu-btn-search').on('click', function () {
                var text = $('.js-menu-input').val();
                searchGamesByText(text)
            });
        }
    }
})();