var $A = {
    sT: undefined, iR: true, vHandlers: {}, $lastSubmitForm: undefined, canPagination: true, priceRangeInit: false,
    init:function(){$(document).ready($A.ready);},
    regHandlers:function(hs,on){  for(var h in hs){
        if (hs.hasOwnProperty(h)) {
            var k = h.split(":", 2); var _k0 = k[0].replace(new RegExp('--','g'), ':');
            on ? $(document).on(k[1],_k0, hs[h]) : $(document).off(k[1],k[0], hs[h]);}
        }
    },
    ready:function(){
        window.addEventListener("popstate", function(){ $A.Query.get({url: location, writeHistory: false}); } );
        $A.regHandlers($A.handlers,true);
        $A.updateView();
        $(window).resize($A.windowResize);
        $(window).scroll($A.windowScroll);
    },
    handlers : {
        "[href]:click"                : function(e){ $A.Query.clickHref(e, this); }, // Переход по ссылкам
        "[type='submit']:click"       : function(e){ $A.Query.clickSubmitBtn(e, this); }, // Отправка формы
        "body:click"                  : function(e){  },
        ".sliderBtn.next:click"       : function() { $A.sliderBtnNextClick(this); },
        ".sliderBtn.prev:click"       : function() { $A.sliderBtnPrevClick(this); },
        "nav > ul > li:click"         : function(e){ $A.dropdownMenuClick(e, this); },
        ".genderFilter:change"        : function() { $A.addGenderFilter(this); },
    },
    updateView: function() {
        $A.initLightSlider();
        $A.initRange();
    },
    messageBox: function(type, msg) { console.log(type, msg); },
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
            $A.Query.get({url: Url.setParam(p) || location.pathname, writeHistory: true });
        }else{
            $A.Query.get({url: Url.removeParam([el.id]) || location.pathname, writeHistory: true });
        }
    },
    initRange: function() {
        var priceRange = $("#priceRange");
        // if($A.priceRangeInit) return;
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
                $A.Query.get({url: Url.setParam({
                    price_from : ui.values[0],
                    price_to   : ui.values[1]
                }), writeHistory: true });
            }
        });
        $( "#amount" ).val( priceRange.slider( "values", 0 ) + " - " + priceRange.slider( "values", 1 ) + 'грн' );
        // $A.priceRangeInit = true;
    }
};



$(window).ready(function(){
    localStorage.ggDisplay == 'block' ?  $('#gg').show() : $('#gg').hide();
    if(localStorage.ggPos) $('#gg').css('background-position-y', localStorage.ggPos);
    $('.sliderBox').width($('.sliderItem').length*33 + 'vw');
    if($('.projectsPage').length){
        $('.projectsPage .item p').each(function(){
            $(this).attr('data-height', $(this).height()/$(window).width());
            if($(this).height()/$(window).width() > 0.1){
                $(this).addClass('hidePart');
            }
        });
    }
});
$(document).on('keydown', function(e){
    var gg = $('#gg');
    var pos = parseInt($('#gg').css('background-position-y'));
    if(e.which==219) { $('#gg').css('background-position-y', pos + 10 + 'px'); }
    if(e.which==221) { $('#gg').css('background-position-y', pos - 10 + 'px'); }
    if(e.which==222) {
        if(localStorage.ggDisplay == 'none'){ gg.show(); localStorage.ggDisplay = 'block'; }
        else{ gg.hide(); localStorage.ggDisplay = 'none'; }
    }
    localStorage.ggPos = gg.css('background-position-y');
});