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
            var key;
            if(!rd) { console.error('Пустой ответ сервера'); return false; }
            console.log('Ответ сервера: ', rd);
            if ( this.writeHistory ){
                var urlArr = this.url.split('?');
                var clearUrl = urlArr[0] + Url.removeParam(['_'], '?' + urlArr[1]);
                if(location.pathname + location.search !== clearUrl) history.pushState('', '', clearUrl);
            }
            if(rd.flash){ a.MessageBox('S', rd.flash); }
            if(rd.renders){
                for(key in rd.renders){
                    if (rd.renders.hasOwnProperty(key)) {
                        var $key = $(key);
                        if(      rd.renders[key].type == "rp"){
                            $key.html(rd.renders[key].render);
                        } else if (rd.renders[key].type == "ap"){
                            $key.append(rd.renders[key].render);
                        } else if (rd.renders[key].type == "pp"){
                            $key.prepend(rd.renders[key].render);
                        }
                    }
                }
                a.updateView();
            }
            if(rd.meta){
                for(key in rd.meta){
                    if (rd.meta.hasOwnProperty(key)){
                        if(key == "title"){ $(key).html(rd.meta[key]); }
                        else{
                            if ( $("meta[name='" + key + "']").length ) $("meta[name='" + key + "']").attr("content", rd.meta[key]);
                            else $('<meta name="' + key + '" content="' + rd.meta[key] + '">').insertAfter("head meta:last");
                        }
                    }
                }
            }
            this.afterSuccess(rd.backData);
        },
        error : function(xhro){
            try {
                if(xhro.responseText){
                    var res = JSON.parse(xhro.responseText);
                    if(res.backData){ a.Validator.serverErrors(res.backData); }
                }
            } catch (e) {
                a.MessageBox('E', 'Ошибка сервера');
                console.log(e);
                a.iR = true;
            }
        },
        statusCode:{
            301: function (xhro){
                if(xhro.getResponseHeader('X-Redirect')){ window.location.href = xhro.getResponseHeader('X-Redirect'); }
            },
            302: function(xhro){
                var res = JSON.parse(xhro.responseText);
                if(res && res.flash){ a.MessageBox('S', res.flash); }
                if(xhro.getResponseHeader('X-Redirect')){
                    a.iR = true;
                    a.Query.get({url: xhro.getResponseHeader('X-Redirect'), preloader: false, writeHistory: true});
                }
            },
            400: function(xhro) {
                var res = JSON.parse(xhro.responseText);
                if(res && res.flash){ a.MessageBox('N', res.flash);}
            },
            500: function(xhro) {
                var res = JSON.parse(xhro.responseText);
                if(res && res.flash){ a.MessageBox('E', res.flash);}
            }
        },
        beforeSend: function() {
            a.iR = false;
            if(this.preloader) $('#preloader').fadeIn(300);
        },
        complete: function() {
            a.iR = true;
            $('#preloader').fadeOut(300);
        }
    },
    get : function (p) {
        if (p.notBlock) {$.ajax(p); a.iR = true; return;}
        if (a.iR){
            clearTimeout(a.sT);
            a.sT = setTimeout(function() {$.ajax(p)}, 300);
        }
    },
    post : function (p) {
        p.type = 'POST';
        p.data.append('_csrf', $('meta[name="csrf-token"]').attr("content"));
        if(p.notBlock) {$.ajax(p); a.iR = true; return;}
        if(a.iR){
            clearTimeout(a.sT);
            a.sT = setTimeout(function() {$.ajax(p)}, 300);
        }
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
        var repeatedClick = $submitBtn.hasClass('stop');
        if( !form.attr('method') ) { console.log('Не указан method отправки формы'); return;}
        var method = form.attr('method').toLowerCase();
        if($submitBtn.attr('data-success-submit-func')){ var submitFunc = $submitBtn.attr('data-success-submit-func').split('.'); }
        if(fd){
            $submitBtn.addClass('stop');
            if(!repeatedClick) {
                a.Query[method]({url: form.attr('action'), data: fd,
                    afterSuccess: function(res) {
                        $submitBtn.removeClass('stop');
                        try{ a[submitFunc[0]][submitFunc[1]](res); }catch(e){}
                    }
                });
                setTimeout(function() { $submitBtn.removeClass('stop'); }, 300);
                console.log('Форма отправлена на: ', form.attr('action'), ' методом ', form.attr('method'));
            }
        }else{console.log('Форма не отправлена! Найдены ошибки: ', form.find('.invalid'));}
    },
    clickHref : function(e, link) {
        var $link = $(link);
        var url = $link.attr('href');
        if( url.slice(0, 4) != 'http'){
            e.preventDefault();
            if(url[0] !="#"){
                a.Query.get({url: url, writeHistory: true});
                console.log('Get запрос на ', url);
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
    getParam: function (getParam, fullSearch) {
        var search = fullSearch || this.search || location.search;
        var regExp = new RegExp( getParam + '=.*?(?=(&|$))', 'i' );
        var value = false;
        if (search.match(regExp)) {
            value = search.match(regExp)[0].split('=')[1];
        }
        return decodeURIComponent(value);
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