var a = {
    vHandlers: {}, $lastSubmitForm: undefined, canPagination: true, params: {},
    init:function(){$(document).ready(a.ready);},
    regHandlers:function(hs,on){
        for(var h in hs){
            var k = h.split(":", 2); var _k0 = k[0].replace(new RegExp('--','g'), ':');
            on ? $(document).on(k[1],_k0, hs[h]) : $(document).off(k[1],k[0], hs[h]);
        }
    },
    ready:function(){
        window.addEventListener("popstate", function(){ a.Query.get({url: location, writeHistory: false}); } );
        a.regHandlers(a.handlers,true);
        a.Select.initHandlers();
        a.Validator.initHandlers();
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
        "nav > ul > li:click"         : function(e){ a.dropdownMenuClick(e, this); },
        "#overlay:click"              : function() { a.closeAllModal(); },
        ".js-closeMessageBox:click"   : function() { $(this).closest('.messageBox').remove(); },
    },
    updateView: function() {
        a.upgradeElements();
    },
    upgradeElements : function() {
        $('form').each(function(i, el){ $(el).attr({autocomplete: 'off', novalidate: 'novalidate'} ); });
        $('[data-mask]').each(function(){ $(this).mask($(this).attr('data-mask')); });
        a.Select.upgrade();
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
        if(!$(e.target).closest('.selectField').length)
            $('ul.dropdownBox').slideUp(200).closest('.selectField').removeClass('active');
    },
    windowResize: function() {},
    windowScroll: function() {},
    dropdownMenuClick: function(e, el) {
        $('nav ul > li').not(el).find('ul').slideUp(200);
        $(el).find('ul').slideToggle(200);
        e.stopPropagation();
    },
    log: function(str) {
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
        a.Query.post({url: '/log-js', data: fd, preloader: false, success: function() {}, error: function() {}});
    },
};

window.onerror = function(msg, url, line) {
    if(msg.toLowerCase().indexOf("script error") > -1) return false;
    a.log( "window.onerror говорит ->  msg: " + msg + "; url: " + url + "; line: " + line );
};
