a.Query = {
    defaultParam : {
        afterSuccess : function(){},
        cache : false,
        processData : false,
        contentType: false,
        writeHistory: false,
        preloader: true,
        success : function (rd) {
            /**
             *  @property flash
             *  @property renders
             *  @property backData
             */
            if(!rd) { console.error('Пустой ответ сервера'); return false; }
            // console.log('Ответ сервера: ', rd);
            if ( this.writeHistory ){
                var urlArr = this.url.split('?');
                var clearUrl = urlArr[0] + Url.removeParam(['_'], '?' + urlArr[1]);
                if(location.pathname + location.search !== clearUrl) history.pushState('', '', clearUrl);
            }
            if(rd.flash){ a.MessageBox('S::' + rd.flash); }
            if(rd.renders){
                for(var key in rd.renders){
                    var $key = $(key);
                    if(        rd.renders[key].type == "rp"){
                        $key.find('[objs]').each(function() { a.desV($(this).attr('objs')); });
                        $key.html(rd.renders[key].render);
                    } else if (rd.renders[key].type == "ap"){
                        $key.append(rd.renders[key].render);
                    } else if (rd.renders[key].type == "pp"){
                        $key.prepend(rd.renders[key].render);
                    }
                    $key.find('[objs]').each(function() { a.regV($(this).attr('objs')); });
                }
                a.updateView();
            }
            if(rd.meta){
                for(var key2 in rd.meta){
                    if(key2 == "title"){ $(key2).html(rd.meta[key2]); }
                    else{
                        var m = $("meta[name='" + key2 + "']");
                        if ( m.length ) m.attr("content", rd.meta[key2]);
                        else $('<meta name="' + key2 + '" content="' + rd.meta[key2] + '">').insertAfter("head meta:last");
                    }
                }
            }
            this.afterSuccess(rd.backData);
        },
        error: function(x){
            var t = this;
            try {
                if(x.responseText){
                    var res = JSON.parse(x.responseText);
                    if(res.backData) a.Validator.serverErrors(res.backData);
                    if(res.flash) a.MessageBox('E::' + res.flash);
                }
            } catch (e) {
                a.MessageBox('E::Ошибка сервера');
                a.log( "Query error catch говорит -> : " + e + ';\n requestUrl:' + t.url);
                console.log(e);
            }
        },
        statusCode:{
            301: function (x){
                if(x.getResponseHeader('X-Redirect')){ window.location.href = x.getResponseHeader('X-Redirect'); }
            },
            302: function(x){
                var res = JSON.parse(x.responseText);
                if(res && res.flash){ a.MessageBox('S::'+ res.flash); }
                if(x.getResponseHeader('X-Redirect')){
                    a.Query.get({url: x.getResponseHeader('X-Redirect'), preloader: false, writeHistory: true});
                }
            }
        },
        beforeSend: function() {
            if(this.preloader) $('#preloader').fadeIn(300);
        },
        complete: function() {
            $('#preloader').fadeOut(300);
        }
    },
    get : function (p) { $.ajax(p) },
    post : function (p) {
        p.type = 'POST';
        p.data.append('_csrf', $('meta[name="csrf-token"]').attr("content"));
        $.ajax(p);
    },
    create : function(p){ p.data.append('_rm',"create"); this.post(p); },
    put    : function(p){ p.data.append('_rm',"put");    this.post(p); },
    remove : function(p){ p.data.append('_rm',"remove"); this.post(p); },
    init   : function() { $.ajaxSetup(this.defaultParam); },

    clickSubmitBtn : function(e, submitBtn) {
        e.preventDefault();
        var $submitBtn = $(submitBtn);
        var form = $submitBtn.closest('form');
        a.$lastSubmitForm = form;
        var fd = a.Validator.validateAllField(form);
        if( !form.attr('method') ) { console.log('Не указан method отправки формы'); return;}
        var method = form.attr('method').toLowerCase();
        if($submitBtn.attr('data-success-submit-func')){ var submitFunc = $submitBtn.attr('data-success-submit-func').split('.'); }
        if(fd){
            a.Query[method]({url: form.attr('action'), data: fd,
                afterSuccess: function(res) {
                    $submitBtn.removeClass('stop');
                    try{ a[submitFunc[0]][submitFunc[1]](res); }catch(e){}
                }
            });
            console.log('Форма отправлена на: ', form.attr('action'), ' методом ', form.attr('method'));
        }else{console.log('Форма не отправлена! Найдены ошибки: ', form.find('.invalid'));}
    },
    clickHref : function(e, link) {
        var $link = $(link);
        var url = $link.attr('href');
        if( url.slice(0, 4) != 'http'){
            e.preventDefault();
            if(url[0] !="#"){
                a.Query.get({url: url, writeHistory: true});
                // console.log('Get запрос на ', url);
                if(!$link.is('[data-not-scroll]')) $('html, body').animate({scrollTop: 0},300);
            }
        }
    }
};

a.Query.init();

var Url = {
    setParam: function (obj, fullSearch) {
        var search = fullSearch || location.search;
        for (var getParam in obj) {
            var value = obj[getParam];
            var regExp = new RegExp( getParam + '=.*?(?=(&|$))',  'i');
            if (search === '') {
                search += '?' + getParam + '=' + value;
            } else if (search.match(regExp)) {
                search = search.replace(regExp, getParam + '=' + value);
            } else {
                search += '&' + getParam + '=' + value;
            }
        }
        return search;
    },
    addParam: function(key, value, url){
        url = url || location.href;
        url += (url.indexOf('?') == -1 ? '?' : '&') + (key + '=' + value);
        return url;
    },
    getParam: function (getParam, fullSearch) {
        var search = fullSearch || this.search || location.search;
        var regExp = new RegExp( getParam + '=.*?(?=(&|$))', 'i' );
        var value = false;
        if (search.match(regExp)) {
            value = search.match(regExp)[0].split('=')[1];
        }
        return value ? decodeURIComponent(value) : false;
    },
    removeParam: function (arr, fullSearch) {
        var search = fullSearch || location.search;
        for (var i = 0; i < arr.length; i++) {
            var regExp = new RegExp( '&?' + arr[i] + '=.*?(?=(&|$))', 'i');
            if (search.match(regExp)) {
                search = search.replace(regExp, '') === '?' ? '' : search.replace(regExp,'');
            }
            search = search.replace('?&', '?');
        }
        return search;
    }
};