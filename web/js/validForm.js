 function ValidForm() {
	this.events();
 }

 ValidForm.prototype.events = function() {
	$('#pass').on('keyup', this.validPass);
	$('#pass').on('focusout', this.validPass);
	$('#confirmPass').on('focusout', this.validPassConfirm);
	$('input[type="text"]').on('focusout', this.validTextFields);
	$('#userPhone').on('focusout', this.validPhone);
	$('#email').on('focusout', this.validEmail);
 };

 ValidForm.prototype.validPass = function() {
	this.password = $(this).val();
	this.s_letters = /[a-z а-я іїє]/;
	this.b_letters = /[A-Z А-Я ІЇЄ]/;
	this.digits = /[0-9 !@#\$%\^&*()_\-\+=|\\\/.,:;\[\]\{\}]/;
	this.rating = 0;
	this.mess = [
		{ width: '0',    color: 'red'    },
		{ width: '25%',  color: 'red'    },
		{ width: '50%',  color: 'orange' },
		{ width: '75%',  color: 'blue'   },
		{ width: '100%', color: 'green'  }];

	if (this.password.length > 0) {
		this.rating++;
		if (this.password.length < 6) {
			$(this).parent().addClass('noValid');
		} else {
			$(this).parent().removeClass('noValid');
			if (this.s_letters.test(this.password)) {
				this.rating++;
			}
			if (this.b_letters.test(this.password)) {
				this.rating++;
			}
			if (this.digits.test(this.password)) {
				this.rating++;
			}
		}
	} else {
		this.rating = 0;
		$(this).parent().addClass('noValid');
	}

	$('label[for=' + $(this).attr('id').replace('#', '') + ']').find('.indicator').css({
		'width': this.mess[+this.rating].width,
		'backgroundColor': this.mess[+this.rating].color
	});
 };

 ValidForm.prototype.validPassConfirm = function() {
	if ($(this).val().length && $(this).val() === $('#pass').val()) {
		$(this).parent().removeClass('noValid');
	} else {
		$(this).parent().addClass('noValid');
	}
 };

 ValidForm.prototype.validTextFields = function() {
	if ($(this).val().length >= 2) {
		if ($(this).attr('id') === "login" && /[а-я А-Я їЇіІёЁєЄ]/.test($(this).val())) {
			$(this).parent().addClass('noValid');
		} else {
			$(this).parent().removeClass('noValid');
		}
	} else {
		$(this).parent().addClass('noValid');
		return false;
	}
 };

 ValidForm.prototype.validPhone = function() {
	if ($(this).val().length === 19 && $(this).val().indexOf('_') == -1) {
		$(this).parent().removeClass('noValid').addClass('input--filled');
	} else {
		$(this).parent().removeClass('input--filled').addClass('noValid');

	}
 };

 ValidForm.prototype.validEmail = function() {
	this.email = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
	if (this.email.test($(this).val())) {
		$(this).parent().removeClass('noValid');
	} else {
		$(this).parent().addClass('noValid');
	}
 };