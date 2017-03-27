var app = {
    sT: undefined, iR: true, vHandlers: {}, $lastSubmitForm: undefined, canPagination: true, 
    init:function(){$(document).ready(a.ready);},
    regHandlers: function(hs,on){
        for(var h in hs){
            if (hs.hasOwnProperty(h)) {
                var k = h.split(":", 2); var _k0 = k[0].replace(new RegExp('--','g'), ':');
                on ? $(document).on(k[1],_k0, hs[h]) : $(document).off(k[1],k[0], hs[h]);
            }
        }
    },
    ready:function(){
        window.addEventListener("popstate", function(){ a.Query.get({url: location, writeHistory: false}); } );
        a.regHandlers(a.handlers,true);
        a.updateView();
        $(window).resize(a.windowResize);
        $(window).scroll(a.windowScroll);
        if($('span.flashError').length > 0)  { a.MessageBox('E', $('span.flashError').text());  }
    },
    handlers : {
        "[href]:click"                          : function(e){ a.Query.clickHref(e, this);}, // Переход по ссылкам
        "[type='submit']:click"                 : function(e){ a.Query.clickSubmitBtn(e, this);}, // Отправка формы
        "body:click"                            : function(e){ a.clickBody(e); },
        "body:keyup"                            : function(e){ if(e.charCode==27) AppJS.closeModal(); },
        ".js-logout:click"                      : function() { var fd = new FormData(); fd.append('_prm', 'logout'); a.Query.post({url: '/admin/logout', data: fd})},
        "nav:mouseleave"                        : function() { $('nav ul ul').slideUp(200); $('.js-userMenu').hide();},
        "nav .js-showSubMenu:click"             : function() { $(this).next().slideToggle(200); },
        "[required]:focusout"                   : function() { a.Validator.checkRequiredVal(this); },
        "[pattern]:focusout"                    : function() { a.Validator.checkFieldToPattern(this); },
        "[data-only-pattern]:keypress"          : function(e){ a.Validator.inputOnlyPattern(e, this); },
        "[name='confirm_password']:focusout"    : function() { a.Validator.confirmPassword(this); },
        ".js-openUserMenuBtn:click"             : function() { $('.js-userMenu').show(); },
        ".js-userMenu .icon-cross:click"        : function() { $('.js-userMenu').hide(); },
        ".selectField .viewBox:click"           : function() { a.selectViewBoxClick(this); },
        ".selectField li:click"                 : function() { a.selectLiClick(this); },
        ".invalid select:change"                : function() { $(this).closest('.invalid').removeClass('invalid').find('p.error').remove(); }, // Убираем ошибку селекта при вводе
        ".js-openDropmenu:click"                : function() { $(this).next().slideToggle(200); },
        ".js-closeModal:click"                  : function() { a.closeModal(this);},
        ".js-overlay, .js-closeConfirmBox:click": function() { a.closeModal(); },
        "input[type='file']:change"             : function(e){ a.InputFile.change(this, e.target.files);},
        ".fileField:dragover"                   : function(e){ e.preventDefault(); e.stopPropagation(); return false;}, // Это нада шоб работал драгендроп
        ".fileField:dragleave"                  : function(e){ e.preventDefault(); e.stopPropagation(); return false;}, // Это нада шоб работал драгендроп
        ".fileField:drop"                       : function(e){ e.preventDefault(); e.stopPropagation(); a.InputFile.change($(this).find('input')[0], e.originalEvent.dataTransfer.files);return false;},
        ".fileField .preview:click"             : function() { a.InputFile.delPreview(this) },
        ".js-plusPrevInputBoxBtn:click"         : function() { a.plusPrevInputBoxBtn(this); },
        ".js-closeMessageBox:click"             : function() { $(this).closest('.messageBox').remove(); },
        ".js-addBlock:click"                    : function() { if(!$('.js-overlay').length) $('<div class="js-overlay"></div>').appendTo('body'); $(this).prev().fadeIn(200); },
        ".js-delRowParams:click"                : function() { $(this).closest('.row').remove();  },
        ".js-delTableRow:click"                 : function() { $(this).closest('tr').remove(); },
        '#search:input'                         : function() { a.search(this);  }, // Поиск в таблицах
        ".js-delCategory:click"                 : function() { a.delCategoryClick(this); },
        ".categoryTablePage input:change"       : function(){ a.categoryTableInputChange(this); }
    },
    updateView: function() {
        a.upgradeElements();
        a.InputFile.obj = {};
        a.closeModal();
        if($('.ProductTablePage').length) $('.search.textField').addClass('active');
        else $('.search.textField').removeClass('active');
    },
    MessageBox: function(type, msg) {
        var key = msg.slice(0, 2);
        if (key == 'S:') type = 'S'; else if(key == 'N:') type = 'N'; else if(key == 'E:') type = 'E';
        msg = msg.replace('S:', '').replace('N:', '').replace('E:', '');
        var messageBox =  $('<div class="messageBox ' + type + '"><i class="icon-cross js-closeMessageBox"></i><i class="icon icon-msgBox-' + type + '"></i><p>' + msg.slice(0, 100) + '</p></div>');
        messageBox.appendTo('body');
        messageBox.addClass('show');
        setTimeout(function() { messageBox.addClass('hide') }, 4000);
        setTimeout(function() { messageBox.remove()         }, 4400);
    },
    search: function(el) {
        if( $(el).val().length > 0 ) {
            var searchStr = $(el).val().replace(/#/g, '');
            if(/^[a-zA-Zа-яА-Я0-9 ёЁЇїІіЄєҐґ\(\)\.\,\;\-\s\:\_]+$/.test(searchStr)){
                a.Query.get({
                    url: Url.removeParam(['per-page', 'page']).setParam({search: searchStr}).getSearch(),
                    writeHistory: true, notBlock: true, preloader: false
                });
            }else{
                a.messageBox('N', 'Недопустимые символы');
            }
        } else {
            a.Query.get({url: Url.removeParam(['per-page', 'page', 'search']).getSearch() || location.pathname, writeHistory: true, notBlock: true});
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
          '     <div class="head">'+
          '         <i class="icon icon-camera"></i>'+
          '         <span class="title">' + p.title + '</span>'+
          '         <div class="btns"><i class="icon icon-check js-ok ' + p.classOk + '"></i><i class="icon icon-cross js-closeModal"></i></div>'+
          '     </div>'+
          '     <div class="body">' + p.content + '</div>'+
          ' </div>').appendTo('body');
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
    upgradeElements : function() {
        $('form').each(function(i, el){ $(el).attr({autocomplete: 'off', novalidate: 'novalidate'} ); }); // Запрет автодополнения полей
        $('select:not(.initialized)').each(function() {
            var selectBox = $(this).closest('.selectField');
            var dropdownBox = '';
            var selectedText = $(this).find('option:selected').text();
            selectBox.find('select option').each(function() { dropdownBox += '<li>'+$(this).text()+'</li>'; });
            $('<div class="viewBox">' + selectedText +'</div><ul class="dropdownBox">' + dropdownBox + '</ul>').appendTo(selectBox);
            $(this).addClass('initialized');
        });
        $('[placeholder]:not(.initialized)').each(function() {
            if( !$(this).siblings('label').length ){
                $('<label class="title">' + $(this).attr("placeholder") + '</label>').insertBefore(this);
            }
        });
        $('input:file:not(.initialized)').each(function(){ a.InputFile.buildField(this); });
        $('[data-mask]').each(function(){ $(this).mask($(this).attr('data-mask')); });
        a.ckeditorInit();
    },
    ckeditorInit: function() {
        $('.js-ckeditor:not(.ckeditorInit)').each(function(){
            var textarea = this;
            if(!textarea.id) textarea.id = 'ckeditor' + parseInt(Math.random()*1000);
            CKEDITOR.replace( this.name );
            CKEDITOR.instances[ textarea.id ].on('change', function() { $('textarea#' + textarea.id).val(this.getData()); });
            $(textarea).addClass('ckeditorInit');
        });
    },
    selectLiClick: function(li) {
        var selectBox = $(li).closest('.selectField');
        var select = selectBox.find('select');
        var clickIndex = $(li).index();
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
    plusPrevInputBoxBtn: function(el) {
        var prevInputBox = $(el).parent().prev();
        prevInputBox.clone().insertBefore($(el).parent());
    },
    delCategoryClick: function(el){
        var categoryId = $(el).closest('tr').attr('category-id');
        a.ConfirmBox({
            title: 'Вы дествительно хотите удалить эту категорию?',
            action: '/admin/category/' + categoryId
        });
    },
    categoryTableInputChange: function(el){
        var categoryId = $(el).closest('tr').attr('category-id');
        var parentId = $(el).closest('tr').attr('parent-id');
        var fd = new FormData();
        fd.append('category_title_ru', $(el).closest('tr').find('[name="category_title_ru"]').val());
        fd.append('category_title_uk', $(el).closest('tr').find('[name="category_title_uk"]').val());
        fd.append('parent_id', parentId);
        a.Query.put({url: '/admin/category/' + categoryId, data: fd});
    }
};
