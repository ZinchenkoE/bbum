a.Validator = {
    patterns: {
        fullname: /^[а-яА-ЯёЁa-zA-Z\s-]{2,100}$/,
        email: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/,
        login: /^[aA-zZ0-9аА-яЯ ]+$/,
        text: /^[\wаА-яЯ ёЁЇїІіЄє,.\s\-+()"!№$%*;:?]+|^$/,
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
            a.Validator.addError(a.Validator.message.emptyField, inputBox);
        }  else {
            a.Validator.removeError(el);
        }
    },
    checkFieldToPattern: function(el) {
        var inputBox = $(el).closest('.inputBox');
        var pattern = $(el).attr('pattern');
        if (!a.Validator.patterns[pattern].test($(el).val())) {
            a.Validator.addError(a.Validator.message[pattern], inputBox);
        } else {
            a.Validator.removeError(el);
        }
    },
    inputOnlyPattern: function(e, el) {
        var inputBox = $(el).closest('.inputBox');
        var pattern = $(el).attr('data-only-pattern');
        var val = $(el).val() + String.fromCharCode(e.charCode);
        var test = a.Validator.patterns[pattern].test(val);
        if (!test) {
            a.Validator.addError(a.Validator.message[pattern], inputBox);
            e.preventDefault();
        }  else {
            a.Validator.removeError(el);
        }
    },
    confirmPassword: function(el) {
        var inputBox = $(el).closest('.inputBox');
        var confirmValue = $(el).val();
        var newPass = $(el).closest('form').find('[name="password"]').val();
        if ( confirmValue !== newPass ) {
            a.Validator.addError(a.Validator.message['passVerification'], inputBox);
        } else {
            a.Validator.removeError(el);
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
            if(!$(this).attr('name')) return; // Отсекаем поля без нейма

            if( elType === "checkbox" ) {
                fd.append(this.name, (this.checked)? 1 : 0);
            }else if( elType === "radio" ){
                if(this.checked){ fd.append(this.name, this.value);}
            }else if( elType === "file" ){
                if(a.InputFile.obj[this.name]) fd.append(this.name,  a.InputFile.obj[this.name]);
            }else {
                fd.append(this.name, this.value);
            }
        });

        if ( $form.find('.invalid').length ) { return false; }
        else { return fd; }
    },
    serverErrors: function (obj) {
         console.log(obj);
        if(a.$lastSubmitForm){
            if(obj.error && a.$lastSubmitForm){
                $.each(obj.error, function(key, val){
                    console.log('C сервера пришла ошибка: ', key, val[0]);
                    var el = a.$lastSubmitForm.find('[name="'+ key +'"], [name="'+ key +'[]"]');
                    var inputBox = el.closest('.inputBox');
                    inputBox.find('p.error').remove();
                    a.Validator.addError(val[0], inputBox);
                });
            }
            a.$lastSubmitForm.find('.js-step').hide().first().show();
        }
    }
};