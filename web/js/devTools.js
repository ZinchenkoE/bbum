$(document).ready(function() {
    if(a.ip != "127.0.0.1" ) return false;

    var gg = $('#gg');

    $('<style>'+
      '    #gg{ width: 100%; position: fixed;border: 1px solid #000;height: 5000px;left: 0;z-index: 1000;top: 60vh;'+
      '        display: block;'+
      '        background: url("/css/gg.jpg") 0 -7.2vw no-repeat;background-size: contain;'+
      '    }'+
      '</style>').appendTo('head');


    localStorage.ggDisplay == 'block' ? gg.show() : gg.hide();
    if (localStorage.ggPos) $('#gg').css('background-position-y', localStorage.ggPos);
    $('.sliderBox').width($('.sliderItem').length * 33 + 'vw');
    if ($('.projectsPage').length) {
        $('.projectsPage .item p').each(function () {
            $(this).attr('data-height', $(this).height() / $(window).width());
            if ($(this).height() / $(window).width() > 0.1) {
                $(this).addClass('hidePart');
            }
        });
    }

    $(document).on('keydown', function (e) {
        var pos = parseInt(gg.css('background-position-y'));
        if (e.which == 219) gg.css('background-position-y', pos + 10 + 'px');
        if (e.which == 221) gg.css('background-position-y', pos - 10 + 'px');
        if (e.which == 222) {
            if (localStorage.ggDisplay == 'none') {
                gg.show();
                localStorage.ggDisplay = 'block';
            } else {
                gg.hide();
                localStorage.ggDisplay = 'none';
            }
        }
        localStorage.ggPos = gg.css('background-position-y');
    });
});
function getData (url) {
    console.log(Url.addParam('get-data-as', 'json', url));
    a.Query.get({url: Url.addParam('get-data-as', 'json', url), success: function(data) {
        console.log(data);
    }});
}
function getInfo () {
    for(var i in a){
        if(typeof(a[i]) != 'function') console.log(i, ' - ', a[i]);
    }
}