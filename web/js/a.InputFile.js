a.InputFile = {
    fileAccept: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, .pdf, .docx',
    obj:{},
    fileField: undefined,
    change: function(fileField, files) {     // Функция для события выбора файлов
        a.InputFile.fileField = $(fileField);
        if (/^image\//.test(files[0].type)) {    // Если картинка
            if(a.InputFile.fileField.attr('data-crop-img')) a.InputFile.cropWithJcrop(files[0]);
            else a.InputFile.cropWithCanvas (files[0]); // Сжимаем картинку чтоб большая сторона была не больше 1600пх
        } else {
            a.InputFile.obj[a.InputFile.fileField[0].name] = files[0];
            if(files[0].type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')      a.InputFile.showFilePreview('doc', files[0].name);
            else if(files[0].type == 'application/pdf')                                                         a.InputFile.showFilePreview('pdf', files[0].name);
            else if(files[0].type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')       a.InputFile.showFilePreview('xls', files[0].name);
        }
    },
    cropWithCanvas: function (fileImg) {
        var img = new Image();
        var reader = new FileReader();
        var canvas = document.createElement('canvas');
        var max = 1600;
        reader.onload = function(e) { img.src = e.target.result; };
        reader.readAsDataURL(fileImg);
        img.onload = function () {
            var width =  img.width;
            var height = img.height;
            if(     img.width >max && img.height<max){width=max; height=max*img.height/img.width;}
            else if(img.height>max && img.width <max){height=max; width=max*img.width/img.height;}
            else if(img.height>max && img.width >max){
                if(img.width>img.height){width=max; height=max*img.height/img.width;}
                else if(img.height>img.width){height=max; width=max*img.width/img.height;}
            }
            canvas.width  = width;
            canvas.height  = height;
            var ctx = canvas.getContext('2d');
            ctx.fillStyle = "rgb(255, 255, 255)";
            ctx.fillRect(0, 0, width, height);
            ctx.drawImage(img, 0, 0, width, height);
            a.InputFile.saveImg(canvas);
        }
    },
    cropWithJcrop: function(fileImg) {
        var img = new Image();
        var reader = new FileReader();
        var cutSize = a.InputFile.fileField.attr('data-crop-img');
        var aspectRadio = a.InputFile.fileField.attr('data-aspect-radio') ? a.InputFile.fileField.attr('aspect-radio') : 1;
        
        img.id = 'targetCrop';
        reader.onload = function(e) { img.src = e.target.result;};
        reader.readAsDataURL(fileImg);
        $('#preloader').show();
        img.onload = function() {
            var getCropParam = function(c) {cropParam = {x: c.x, y: c.y, w: c.w, h: c.h};};
            var cropParam = {x: 0, y: 0, w: 200, h:200};
            a.ModalBox({
                title: 'Обрезка изображения',
                content: img.outerHTML,
                classModal: 'jcrop',
                classOk: 'js-okJcropModal'
            });
            $('.js-okJcropModal').click(function() {
                var canvas = document.createElement('canvas');
                canvas.width  = cutSize*aspectRadio;
                canvas.height = cutSize;
                canvas.getContext('2d').drawImage(
                    img,
                    -cropParam.x*cutSize*aspectRadio/cropParam.w,
                    -cropParam.y*cutSize/cropParam.h,
                    viewImgW*cutSize*aspectRadio/cropParam.w,
                    viewImgH*cutSize/cropParam.h
                );
                a.InputFile.saveImg(canvas);
                a.InputFile.fileField.val('');
                a.closeModal();
            });
            var targetCrop = $('#targetCrop');
            var viewImgW = targetCrop.width();
            var viewImgH = targetCrop.height();
            targetCrop.Jcrop({  setSelect: [0, 0, 200, 200],  onChange: getCropParam,  aspectRatio: aspectRadio});
            $('#preloader').hide();
        }
    },
    saveImg: function (canvas) {
        var data = canvas.toDataURL('image/jpeg');
        var aBase64 = data.split(',');
        var sData = atob(aBase64[1]);
        var aBufferView = new Uint8Array(sData.length);
        for (var i = 0; i < aBufferView.length; i++) { aBufferView[i] = sData.charCodeAt(i); }
        a.InputFile.obj[a.InputFile.fileField[0].name] = new Blob([aBufferView], {type : 'image/jpeg'});
        a.InputFile.showPreview(data);
    },
    showPreview: function(data) {
        var label = a.InputFile.fileField.parent();
        label.find('.preview').remove();
        label.before('<div class="preview"><img src="' + data + '"><i class="icon icon-trash js-delPreview"></i></div>');
        if(a.InputFile.fileField.hasClass('js-addNextFileField')){
            var widgetIndex = a.InputFile.fileField.closest('.widget').index();
            var fieldIndex = a.InputFile.fileField.closest('.widget').find('.fileField:last').index() + 1;
            $('<div class="fileField"><label><input name="w[' + widgetIndex + '][wGallery][' + fieldIndex + ']" type="file" class="js-addNextFileField" accept="image/jpeg, image/png"></label></div>').insertAfter(a.InputFile.fileField.closest('.fileField'));
        }
    },
    showFilePreview: function(format, fileName) {
        var label = a.InputFile.fileField.parent();
        label.find('.preview').remove();
        label.before('<div class="preview"><img src="/img/admin/' + format + '.png"><p>' + fileName + '</p><i class="icon icon-trash js-delPreview"></i></div>');
        if(a.InputFile.fileField.hasClass('js-addNextFileField')){
            var widgetIndex = a.InputFile.fileField.closest('.widget').index();
            var fieldIndex = a.InputFile.fileField.closest('.widget').find('.fileField:last').index() + 1;
            $('<div class="fileField file">' +
                '<label>' +
                    '<input name="w[' + widgetIndex + '][wFiles][' + fieldIndex + ']" type="file" class="js-addNextFileField" accept="' + a.InputFile.fileAccept + '">' +
                '</label>' +
            '</div>').insertAfter(a.InputFile.fileField.closest('.fileField'));
        }
    },
    delPreview: function(el) {
        var input = $(el).siblings('label').find('input:file');
        input.val('');
        if( !!input.attr('data-img-src') ) {
            $('<input type="hidden" name="removeImg[]" value="' + input.attr('data-img-src') + '">').insertAfter(input);
            input.removeAttr('data-img-src');
        }
        $(el).remove(); a.InputFile.obj = {};
    },
    buildField: function() {
        $('input:file:not(.initialized)').each(function(i, el){
            var random = (Math.random() * 100000).toFixed(0);
            if( $(el).attr('data-img-src') ){
                $('<div class="preview"><img src="' + $(el).attr('data-img-src') + '?v=' + random + '"><i class="icon icon-trash js-delPreview"></i></div>').insertBefore($(el).parent());
            }
            if($(el).closest('.fileField').hasClass('file')){
                $(el).attr('accept', a.InputFile.fileAccept);
            } else {
                $(el).attr('accept', 'image/jpeg, image/png');
            }
            $(el).addClass('initialized');
        });

    }
};
