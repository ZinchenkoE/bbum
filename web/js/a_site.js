var a = {
    sT: undefined, iR: true, vHandlers: {}, $lastSubmitForm: undefined, canPagination: true, priceRangeInit: false, params: {},
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
        $('[data-objs]').each(function(){ a.regV($(this).attr('data-objs'));});
        a.updateView();
        $(window).resize(a.windowResize);
        $(window).scroll(a.windowScroll);
    },
    regV: function(v){
        if(!v || !window[v]) return null;
        console.log('Добавлена вьюха: ', v);
        $(document).ready(window[v].ready);
        a.vHandlers[v] = window[v].handlers;
        delete window[v].handlers;
        a.regHandlers(a.vHandlers[v],true);
    },
    desV: function(v){
        if(!v) return null;
        console.log('Удалена вьюха: ', v);
        if(a.vHandlers[v]){ a.regHandlers(a.vHandlers[v], false); delete a.vHandlers[v]; }
        if(window[v]) delete window[v];
    },
    handlers : {
        "[href]:click"                : function(e){ a.Query.clickHref(e, this); }, // Переход по ссылкам
        "[type='submit']:click"       : function(e){ a.Query.clickSubmitBtn(e, this); }, // Отправка формы
        "body:click"                  : function(e){ a.clickBody(e); },
        "[required]:focusout"         : function() { a.Validator.checkRequiredVal(this); },
        "[pattern]:focusout"          : function() { a.Validator.checkFieldToPattern(this); },
        "[data-only-pattern]:keypress": function(e){ a.Validator.inputOnlyPattern(e, this); },
        ".sliderBtn.next:click"       : function() { a.sliderBtnNextClick(this); },
        ".sliderBtn.prev:click"       : function() { a.sliderBtnPrevClick(this); },
        "nav > ul > li:click"         : function(e){ a.dropdownMenuClick(e, this); },
        ".genderFilter:change"        : function() { a.addGenderFilter(this); },
        "#overlay:click"              : function() { a.closeAllModal(); },
        ".js-closeMessageBox:click"   : function() { $(this).closest('.messageBox').remove(); },
        ".selectField .viewBox:click" : function() { a.selectViewBoxClick(this); },
        ".selectField li:click"       : function() { a.selectLiClick(this); },
        ".invalid select:change"      : function() { $(this).closest('.invalid').removeClass('invalid').find('p.error').remove(); },
        ".searchSelectInput:click"    : function(e){ e.stopPropagation();},
        ".searchSelectInput:input"    : function() { a.searchSelectInput($(this)); },
        ".searchSelectIcon:click"     : function(e){ e.stopPropagation(); $(this).text('search').prev().val('').trigger('input');},
    },
    updateView: function() {
        a.initLightSlider();
        a.initRange();
        a.upgradeElements();
    },
    upgradeElements : function() {
        $('form').each(function(i, el){ $(el).attr({autocomplete: 'off', novalidate: 'novalidate'} ); }); // Запрет автодополнения полей
        $('select:not(.initialized)').each(function() {
            var $select = $(this);
            var selectBox = $select.closest('.selectField');
            var dropdownBox = '';
            var selectedText = $select.find('option:selected').text();
            if($select.hasClass('searchSelect'))
                dropdownBox += '<li class="forSearchSelectInput">' +
                                    '<input class="searchSelectInput">' +
                                    '<i class="material-icons searchSelectIcon">search</i>' +
                               '</li>';
            selectBox.find('select option').each(function() {
                var $option = $(this);
                dropdownBox += '<li class="' + ($option.attr('class') || '') + '">' + $option.text() + '</li>';
            });
            $('<div class="viewBox">' + selectedText +'</div><ul class="dropdownBox">' + dropdownBox + '</ul>').appendTo(selectBox);
            $(this).addClass('initialized');
        });
        $('[data-mask]').each(function(){ $(this).mask($(this).attr('data-mask')); });
    },
    MessageBox: function(msg) {
        var type = msg.split('::')[0];
        msg = msg.replace('S::', '').replace('N::', '').replace('E::', '');
        var messageBox =  $('<div class="messageBox js-closeMessageBox ' + type + '">' +
                                '<i class="icon-cross"></i>' +
                                '<i class="icon icon-msgBox-' + type + '"></i>' +
                                '<p>' + msg.slice(0, 100) + '</p>' +
                            '</div>');
        messageBox.appendTo('body');
        messageBox.addClass('show');
        setTimeout(function() { messageBox.addClass('hide') }, 3000);
        setTimeout(function() { messageBox.remove()         }, 3400);
    },
    closeAllModal: function() {
        $('#overlay, #Cart').fadeOut(200);
    },
    clickBody: function(e) {
        if(!$(e.target).closest('.selectField').length) $('ul.dropdownBox').slideUp(200).closest('.selectField').removeClass('active');
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
        priceRange.slider({
            range: true,
            min: +priceRange.attr('data-min'),
            max: +priceRange.attr('data-max'),
            values: [ +priceRange.attr('data-value-min'), +priceRange.attr('data-value-max') ],
            slide: function( event, ui ) {
                $("#amount").val( priceRange.slider( "values", 0 ) + " - " + priceRange.slider( "values", 1 ) + 'грн' );
            },
            stop: function(event, ui) {
                a.Query.get({url: Url.setParam({
                    price_from : ui.values[0],
                    price_to   : ui.values[1]
                }), writeHistory: true });
            }
        });
        $("#amount").val( priceRange.slider( "values", 0 ) + " - " + priceRange.slider( "values", 1 ) + 'грн' );
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
    selectLiClick: function(li) {
        var selectBox = $(li).closest('.selectField');
        var select = selectBox.find('select');
        var clickIndex = select.hasClass('searchSelect') ? $(li).index()-1 : $(li).index();
        var targetValue = select.find('option').eq(clickIndex).attr('value');
        selectBox.find('ul.dropdownBox').slideToggle(200).closest('.selectField').toggleClass('active');
        selectBox.find('.viewBox').text($(li).text());
        select.val(targetValue);
        select.change();
    },
    selectViewBoxClick: function(viewBox) {
        var thisSelect = $(viewBox).next();
        $('ul.dropdownBox').not(thisSelect).slideUp(200).closest('.selectField').removeClass('active');
        thisSelect.slideToggle(200).closest('.selectField').toggleClass('active');
    },
    searchSelectInput: function($input){
        var searchStr = $input.val().toLowerCase();
        var selWrap = $input.closest('.inputBox');
        if(searchStr) $input.next().text('close');
        else $input.next().text('search');
        selWrap.find('li:not(:first)').each(function() {
            var $t = $(this);
            if( $t.text().toLowerCase().indexOf(searchStr) === -1 ) $t.hide();
            else $t.show();
        });
    }
};

window.onerror = function(msg, url, line) {
    if(msg.toLowerCase().indexOf("script error") > -1) return false;
    a.log( "window.onerror говорит ->  msg: " + msg + "; url: " + url + "; line: " + line );
};
