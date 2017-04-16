var a = {
    sT: undefined, iR: true, vHandlers: {}, $lastSubmitForm: undefined, canPagination: true, priceRangeInit: false,
    init:function(){$(document).ready(a.ready);},
    regHandlers:function(hs,on){  for(var h in hs){
        if (hs.hasOwnProperty(h)) {
            var k = h.split(":", 2); var _k0 = k[0].replace(new RegExp('--','g'), ':');
            on ? $(document).on(k[1],_k0, hs[h]) : $(document).off(k[1],k[0], hs[h]);}
        }
    },
    ready:function(){
        window.addEventListener("popstate", function(){ a.Query.get({url: location, writeHistory: false}); } );
        a.regHandlers(a.handlers,true);
        a.updateView();
        $(window).resize(a.windowResize);
        $(window).scroll(a.windowScroll);
    },
    handlers : {
        "[href]:click"                : function(e){ a.Query.clickHref(e, this); }, // Переход по ссылкам
        "[type='submit']:click"       : function(e){ a.Query.clickSubmitBtn(e, this); }, // Отправка формы
        "body:click"                  : function(e){  },
        ".sliderBtn.next:click"       : function() { a.sliderBtnNextClick(this); },
        ".sliderBtn.prev:click"       : function() { a.sliderBtnPrevClick(this); },
        "nav > ul > li:click"         : function(e){ a.dropdownMenuClick(e, this); },
        ".genderFilter:change"        : function() { a.addGenderFilter(this); },
    },
    updateView: function() {
        a.initLightSlider();
        a.initRange();
    },
    MessageBox: function(msg) {
        var type = msg.split('::')[0];
        msg = msg.replace('S::', '').replace('N::', '').replace('E::', '');
        var messageBox =  $('<div class="messageBox js-closeMessageBox ' + type + '"><i class="icon-cross"></i><i class="icon icon-msgBox-' + type + '"></i><p>' + msg.slice(0, 100) + '</p></div>');
        messageBox.appendTo('body');
        messageBox.addClass('show');
        setTimeout(function() { messageBox.addClass('hide') }, 3000);
        setTimeout(function() { messageBox.remove()         }, 3400);
    },
    windowResize: function() {},
    windowScroll: function() {},
    initLightSlider: function() {
        $('#imageGallery').lightSlider({
            gallery:true,
            item:1,
            loop:true,
            thumbItem:9,
            slideMargin:0,
            enableDrag: false,
            currentPagerPosition:'left',
            onSliderLoad: function(el) {
                el.lightGallery({
                    selector: '#imageGallery .lslide'
                });
            }
        });
    },
    sliderBtnNextClick: function(el) {
        var slider     = $(el).closest('.slider');
        var position   = +slider.attr('data-position');
        var lastPage   = +slider.attr('data-last-page');
        slider.attr('data-position', position + 1200);
        $('.sliderInner').css('transform', 'translateX(-' + (position + 1200) + 'px)');
        if( (position + 1200) >= lastPage * 1200) $(el).hide();
        $('.sliderBtn.prev').show();
    },
    sliderBtnPrevClick: function(el) {
        var slider     = $(el).closest('.slider');
        var position   = +slider.attr('data-position');
        slider.attr('data-position', position - 1200);
        $('.sliderInner').css('transform', 'translateX(-' + (position - 1200) + 'px)');
        if( (position - 1200) <= 0) $(el).hide();
        $('.sliderBtn.next').show();
    },
    dropdownMenuClick: function(e, el) {
        $('nav ul > li').not(el).find('ul').slideUp(200);  $(el).find('ul').slideToggle(200); e.stopPropagation();
    },
    addGenderFilter: function(el) {
        if(el.checked){
            var p = {};
            p[el.id] = 1;
            a.Query.get({url: Url.setParam(p) || location.pathname, writeHistory: true });
        }else{
            a.Query.get({url: Url.removeParam([el.id]) || location.pathname, writeHistory: true });
        }
    },
    initRange: function() {
        var priceRange = $("#priceRange");
        // if(a.priceRangeInit) return;
        // console.log(77);
        priceRange.slider({
            range: true,
            min: +priceRange.attr('data-min'),
            max: +priceRange.attr('data-max'),
            values: [ +priceRange.attr('data-value-min'), +priceRange.attr('data-value-max') ],
            slide: function( event, ui ) {
                $( "#amount" ).val( priceRange.slider( "values", 0 ) + " - " + priceRange.slider( "values", 1 ) + 'грн' );
            },
            stop: function(event, ui) {
                a.Query.get({url: Url.setParam({
                    price_from : ui.values[0],
                    price_to   : ui.values[1]
                }), writeHistory: true });
            }
        });
        $( "#amount" ).val( priceRange.slider( "values", 0 ) + " - " + priceRange.slider( "values", 1 ) + 'грн' );
        // a.priceRangeInit = true;
    },
    log: function(str) {
        var ctrl = '';
        if(     !location.pathname.search('/company') && location.pathname.search('/company-registration') ) ctrl = '/company';
        else if(!location.pathname.search('/sudo'))    ctrl = '/sudo';
        var browser = (function() {
            var N= navigator.appName, ua= navigator.userAgent, tem;
            var M= ua.match(/(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i);
            if(M && (tem= ua.match(/version\/([.\d]+)/i))!= null) M[2]= tem[1];
            M= M? [M[1], M[2]]: [N, navigator.appVersion,'-?'];
            return JSON.stringify(M)
        })();
        var hw = '; width: ' + $(window).width()  + ' - height: ' + $(window).height();
        str += ' ; ' + location.href + "; browser: " + browser + hw;
        var fd = new FormData();
        fd.append('var', str);
        a.Query.post({url: ctrl + '/log-js', data: fd, notBlock: true, preloader: false, success: function() {}, error: function() {}});
    },
};

window.onerror = function(msg, url, line) {
    if(msg.toLowerCase().indexOf("script error") > -1) return false;
    a.log( "window.onerror говорит ->  msg: " + msg + "; url: " + url + "; line: " + line );
};
