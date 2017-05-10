var a = {
    vHandlers: {}, $lastSubmitForm: undefined,
    init:function(){$(document).ready(a.ready);},
    regHandlers: function(hs,on){
        for(var h in hs){
            var k = h.split(":", 2); var _k0 = k[0].replace(new RegExp('--','g'), ':');
            on ? $(document).on(k[1],_k0, hs[h]) : $(document).off(k[1],k[0], hs[h]);
        }
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
    ready:function(){
        $('[data-objs]').each(function(){ a.regV($(this).attr('data-objs'));});
        window.addEventListener("popstate", function(){ a.Query.get({url: location, writeHistory: false}); } );
        a.regHandlers(a.handlers,true);
        a.Select.initHandlers();
        a.Validator.initHandlers();
        a.updateView();
        $(window).resize(a.windowResize);
        $(window).scroll(a.windowScroll);
        var flashError = $('span.flashError'); if(flashError.length > 0) a.MessageBox('E::' + flashError.text());
    },
    handlers : {
        "[href]:click"                          : function(e){ a.Query.clickHref(e, this);},
        "[type='submit']:click"                 : function(e){ a.Query.clickSubmitBtn(e, this);},
        "body:click"                            : function(e){ a.clickBody(e); },
        "body:keyup"                            : function(e){ if(e.charCode === 27) a.closeModal(); },
        ".js-logout:click"                      : function() { var fd = new FormData(); fd.append('_prm', 'logout'); a.Query.post({url: '/admin/logout', data: fd})},
        "nav:mouseleave"                        : function() { $('nav ul ul').slideUp(200); $('.js-userMenu').hide();},
        "nav .js-showSubMenu:click"             : function() { $(this).next().slideToggle(200); },
        ".js-openUserMenuBtn:click"             : function() { $('.js-userMenu').show(); },
        ".js-userMenu .icon-cross:click"        : function() { $('.js-userMenu').hide(); },
        ".js-openDropmenu:click"                : function() { $(this).next().slideToggle(200); },
        ".js-closeModal:click"                  : function() { a.closeModal(this);},
        ".js-overlay, .js-closeConfirmBox:click": function() { a.closeModal(); },
        "input[type='file']:change"             : function(e){ a.InputFile.change(this, e.target.files);},
        ".fileField:dragover"                   : function(e){ e.preventDefault(); e.stopPropagation(); return false;},
        ".fileField:dragleave"                  : function(e){ e.preventDefault(); e.stopPropagation(); return false;},
        ".fileField:drop"                       : function(e){ e.preventDefault(); e.stopPropagation(); a.InputFile.change($(this).find('input')[0], e.originalEvent.dataTransfer.files);return false;},
        ".fileField .preview:click"             : function() { a.InputFile.delPreview(this) },
        '#search:input'                         : function() { a.search(this);  }, // Поиск в таблицах
        ".js-closeMessageBox:click"             : function() { $(this).closest('.messageBox').remove(); },
    },
    updateView: function() {
        a.upgradeElements();
        a.InputFile.obj = {};
        a.closeModal();
        a.toggleSearchInput();
    },
    upgradeElements : function() {
        a.Select.upgrade();
        a.InputFile.buildField();
        $('form').each(function(){ $(this).attr({autocomplete: 'off', novalidate: 'novalidate'} ); });
        $('[placeholder]:not(.initialized)').each(function() {
            if( !$(this).siblings('label').length ){
                $('<label class="title">' + $(this).attr("placeholder") + '</label>').insertBefore(this);
            }
        });
        $('[data-mask]').each(function(){ $(this).mask($(this).attr('data-mask')); });
        a.ckeditorInit();
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
    toggleSearchInput: function(){
        var search = $('.search.textField');
        if($('#ProductTablePage').length) search.show();
        else search.hide();
    },
    search: function(el) {
        if( $(el).val().length > 0 ) {
            var searchStr = $(el).val().replace(/#/g, '');
            if(/^[\wA-Zа-яА-ЯёЁЇїІіЄє().,;\-\s:]+$/.test(searchStr)){
                var url = Url.removeParam(['per-page', 'page']);
                url     = Url.setParam({search: searchStr}, url);
                a.Query.get({url: url, writeHistory: true, preloader: false });
            }else{
                a.MessageBox('N::Недопустимые символы');
            }
        } else {
            a.Query.get({url: Url.removeParam(['per-page', 'page', 'search']) || location.pathname, writeHistory: true});
        }
    },
    ConfirmBox: function(p) {
        p.method = p.method || 'remove';
        p.successFunc = p.successFunc ? 'data-success-submit-func="' + p.successFunc + '"' : '';
        p.addField = p.addField || '';

        if(!$('.js-overlay').length) $('<div class="js-overlay"></div>').appendTo('body');

        $('<div class="confirmBox">'+
          '    <h2>' + p.title + '</h2>'+
          '    <form action="' + p.action + '" method="' + p.method  + '">'+ p.addField +
          '        <button type="button" class="btn js-closeConfirmBox">Отмена</button>'+
          '        <button type="submit" class="btn">Удалить</button>'+
          '    </form>'+
          '</div>').appendTo('body');
    },
    ModalBox: function(p) {
        if(!$('.js-overlay').length) $('<div class="js-overlay"></div>').appendTo('body');
        $('<div class="modalBox ' + p.classModal + '">'+
          '    <div class="head">'+
          '        <i class="icon icon-camera"></i>'+
          '        <span class="title">' + p.title + '</span>'+
          '        <div class="btns"><i class="icon icon-check js-ok ' + p.classOk + '"></i><i class="icon icon-cross js-closeModal"></i></div>'+
          '    </div>'+
          '    <div class="body">' + p.content + '</div>'+
          '</div>').appendTo('body');
    },
    closeModal: function(el) {
        if(el){
            $(el).closest('.modalBox').remove();
        } else {
            $('.modalBox, .confirmBox').remove();
            $('.choiceWidget').fadeOut(200);
        }
        if( !$('.modalBox').length ) $('.js-overlay').remove();
    },
    clickBody: function(e) {
        if(!$(e.target).closest('.selectField').length) $('ul.dropdownBox').slideUp(200).closest('.selectField').removeClass('active');
        if(!$(e.target).closest('td.btns').length) $('ul.dropmenu').slideUp(200);
    },
    windowResize: function() {},
    windowScroll: function() {},
    ckeditorInit: function() {
        $('.js-ckeditor:not(.ckeditorInit)').each(function(){
            var textarea = this;
            if(!textarea.id) textarea.id = 'ckeditor' + parseInt(Math.random()*1000);
            CKEDITOR.replace( this.name );
            CKEDITOR.instances[ textarea.id ].on('change', function() { $('textarea#' + textarea.id).val(this.getData()); });
            $(textarea).addClass('ckeditorInit');
        });
    },
};
