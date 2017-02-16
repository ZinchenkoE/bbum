$A.Validator = {
    patterns: {
        fullname: /^[а-яА-ЯёЁa-zA-Z\s-]{2,100}$/,
        email: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/,
        login: /^[aA-zZ0-9аА-яЯ ]+$/,
        text: /^[aA-zZ0-9аА-яЯ ёЁЇїІіЄє,\.\s\-\+\(\)"\!\№\$\%\*\;\s\:\—\?]+|^$/,
        // text: /^[aA-zZ0-9аА-яЯ ёЁЇїІіЄєҐґ\.\s\-\+\(]+$/,
        ip: /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/,
        integer: /^[0-9]+$/,
        float: /^\d+(\.?|,?)\d*$/
    },
    message: {
        emptyField: 'Поле не может быть пустым',
        fullname: 'Данное поле должно содержать минимум 2 символа',
        email: 'Неверный формат Email. ',
        passField: 'Пароль слишком прост',
        passVerification: 'Пароли не совпадают',
        passVerificationEmpty: 'Подтвердите пароль',
        text: 'Введены недопустимые символы',
        login: 'Недопустимый Логин, только бувквы цыфры _.',
        checkboxField: 'Необходимо отметить',
        subdomain: 'Недопустимый формат доменa',
        date: 'Неверный формат даты. dd-mm-yyyy',
        ip: 'Неверный формат ip',
        integer: 'Только целые числа',
        float: 'Можно вводить только числа'
    },
    addError: function(str, inputBox) {
        if(!inputBox.hasClass('invalid')) inputBox.addClass('invalid').append('<p class="error">' + str + '</p>');
    },
    removeError: function (elem) {
        $(elem).closest('.invalid').removeClass('invalid').find('p.error').remove();
    },
    checkRequiredVal: function(el) {
        var inputBox = $(el).closest('.inputBox');
        if ($(el).val().trim() === "") {
            $A.Validator.addError($A.Validator.message.emptyField, inputBox);
        }  else {
            $A.Validator.removeError(el);
        }
    },
    checkFieldToPattern: function(el) {
        var inputBox = $(el).closest('.inputBox');
        var pattern = $(el).attr('pattern');
        if (!$A.Validator.patterns[pattern].test($(el).val())) {
            $A.Validator.addError($A.Validator.message[pattern], inputBox);
        } else {
            $A.Validator.removeError(el);
        }
    },
    inputOnlyPattern: function(e, el) {
        var inputBox = $(el).closest('.inputBox');
        var pattern = $(el).attr('only-pattern');
        var val = $(el).val() + String.fromCharCode(e.charCode);
        var test = $A.Validator.patterns[pattern].test(val);
        if (!test) {
            $A.Validator.addError($A.Validator.message[pattern], inputBox);
            e.preventDefault();
        }  else {
            $A.Validator.removeError(el);
        }
    },
    confirmPassword: function(el) {
        var inputBox = $(el).closest('.inputBox');
        var confirmValue = $(el).val();
        var newPass = $(el).closest('form').find('[name="password"]').val();
        if ( confirmValue !== newPass ) {
            $A.Validator.addError($A.Validator.message['passVerification'], inputBox);
        } else {
            $A.Validator.removeError(el);
        }
    },
    validateAllField: function ($form) {
        var fd = new FormData();
        var fields = $form.find('input, textarea, select');
        $form.find('.invalid').removeClass('invalid');
        $form.find('p.error').remove();
        fields.focusout();
        $(fields).each(function () {
            var elType = $(this).attr('type');
            if($(this).attr('name') == undefined) return; // Отсекаем поля без нейма

            if( elType === "checkbox" ) {
                fd.append(this.name, (this.checked)? 1 : 0);
            }else if( elType === "radio" ){
                if(this.checked){ fd.append(this.name, this.value);}
            }else if( elType === "file" ){
                if($A.InputFile.obj[this.name]) fd.append(this.name,  $A.InputFile.obj[this.name]);
            }else {
                fd.append(this.name, this.value);
            }
        });

        if ( $form.find('.invalid').length ) { return false; }
        else { return fd; }
    },
    serverErrors: function (obj) {
         console.log(obj);
        if($A.$lastSubmitForm){
            if(obj.error && $A.$lastSubmitForm){
                $.each(obj.error, function(key, val){
                    console.log('C сервера пришла ошибка: ', key, val[0]);
                    var el = $A.$lastSubmitForm.find('[name="'+ key +'"], [name="'+ key +'[]"]');
                    var inputBox = el.closest('.inputBox');
                    inputBox.find('p.error').remove();
                    $A.Validator.addError(val[0], inputBox);
                });
            }
            $A.$lastSubmitForm.find('.js-step').hide().first().show();
        }
    },
};