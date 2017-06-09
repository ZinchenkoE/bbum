$(document).ready(function() {
		$('.overlay').on('click', function() {
		if ($('.goToBag').hasClass('openMenu')) {
			$('.goToBag').click();
		} else if ($('.goToMenu').hasClass('openMenu')) {
			$('.goToMenu').click();
		}
	});
	$('.forgotPassword').on('click', function() {
		$('.loginBox').hide();
		$('.forgotLogin').show();
	});
	function animatePulseBag() {
		if ($('.boxBag').find('.boxProduct').length && !$('.goToBag').hasClass('openMenu')) {
			$('.goToBag').addClass('pulse');
		} else {
			$('.goToBag').removeClass('pulse');
		}
	}
	animatePulseBag();
	$('body').on('click', '.goToBag', animatePulseBag);
	var menu = new MainPages({
		wrapper: '#wrapper',
		menu: '.menu',
		rightSitebar: '.rightSitebar',
		linkMainMenu: '.linkMainMenu',
		goToMenu: '.goToMenu',
		wrappMenu: '.wrappMenu',
		content: '.content',
		linkMenu: '.linkMenu',
		submenu: '.submenu',
		linkSubmenu: '.linkSubmenu',
		checkLang: '.checkLang',
		activeLang: '.activeLang',
		lang1: '.lang',
		social: '.social'
	});
	//----- end main menu
	// -------- for styles resize window
	$(window).on('resize', function() {
		if ($(window).width() <= 959) {
			if ($('.goToBag').hasClass('openMenu')) {
				$('.boxBag').css({
					'left': 0,
					'paddingTop': $('.rightSitebar').outerHeight()
				});
				$('.boxOrder').css('left', 0);
			} else {
				$('.boxBag, .boxOrder').css({
					'left': -$('.boxBag').outerWidth()
				});
			}
			$('.overlay').hide();

			if ($('.content').hasClass('pageProduct')) {
				$('.goToMenu').css('background', 'none');
			}
		} else {
			if ($('.goToBag').hasClass('openMenu')) {
				$('.boxBag').css({
					'left': $('.rightSitebar').outerWidth(),
					'paddingTop': '1.554vw'
				});
				$('.overlay').show().css({
					'left': $('.rightSitebar').outerWidth() + $('.boxBag').outerWidth()
				});
			} else {
				$('.boxBag').css({
					'left': -($('.boxBag').outerWidth() - $('.rightSitebar').outerWidth())
				});
				$('.overlay').show().css({
					'left': -($('.overlay').outerWidth() - $('.rightSitebar').outerWidth())
				});
			}
			$('body').removeClass('horizontalMob');
			if ($('.content').hasClass('pageProduct')) {
				$('.goToMenu').css({
					'background': 'url(../images/menu.png)',
					'backgroundSize': '100% 100%'
				});
			}
		}
	});
	// ------- end styles resize window
	// ---- for change icon main menu
	$('.linkMainMenu ').on('click', function() {
		if ($(this).hasClass('goToMenu') || $(this).hasClass('goToBag')) {

			if ($(this).parent().siblings().find('.activePage').length) {
				$(this).parent().siblings().find('.activePage').addClass('notActive').removeClass('activePage');
			} else if ($(this).parent().siblings().find('.backBtn').length) {
				$(this).parent().siblings().find('.backBtn').addClass('notBack').removeClass('backBtn');
			} else if ($(this).parent().siblings().find('.notActive').length) {
				$(this).parent().siblings().find('.notActive').addClass('activePage').removeClass('notActive');
			} else if ($(this).parent().siblings().find('.notBack').length) {
				$(this).parent().siblings().find('.notBack').addClass('backBtn').removeClass('notBack');
			}

			if (!$('.goToBag').hasClass('openMenu')) {
				$('.goToBag').removeClass('pulse');
			} else {
				$('.goToBag').addClass('pulse');
			}
		}
	});
	// ------ end icon main menu
	// for index.html page
	$('.category').mouseenter(function() {
		if ($(window).width() <= 959) {
			return false;
		} else {
			var arr = $(this).parent().find('.category');
			$.each(arr, function(index, item) {
				if (item !== this && !$(item).children().hasClass('boxOverlayCategory')) {
					$(item).prepend($('<p></p>').attr({
						'class': 'boxOverlayCategory'
					}));
					$('.boxOverlayCategory').css({
						'width': '100%',
						'height': '100%',
						'backgroundColor': '#22201d',
						'opacity': 0.7,
						'position': 'absolute',
						'top': 0,
						'left': 0,
						'zIndex': 5
					});
				} else {
					$(this).children().remove('.boxOverlayCategory');
					$(item).css({
						'opacity': 1
					});
				}
			}.bind(this));
		}
	});
	$('.productCategory').mouseleave(function() {
		$(this).children().css({
			'opacity': 1
		});
		$(this).find('.boxOverlayCategory').remove('.boxOverlayCategory');
	});
	//----- end index.html page
	// for input Field all pages
	$('.input__field').on('focusout', function() {
		if ($(this).val().length > 0) {
			$(this).parent().addClass('input--filled');
		} else {
			$(this).parent().removeClass('input--filled');
		}
	});
	if ($(window).width() < 960) {
		$('.formAutorization').height($('.boxAutorization').outerHeight(true) - $('h2.title').outerHeight(true));
	}
	$('.pageAutorization .loginBtn').on('click', function(event) {
		$.each($('div[required]').find('input'), function(i, item) {
			if (!$(item).val().length) {
				$(item).parent().addClass('noValid');
			} else {
				$(item).parent().removeClass('noValid');
			}
		});
		if ($('body').find('.noValid').length) {
			event.preventDefault();
			return false;
		}
	});
	// for plugin mask input (phone format)
	if ($('#userPhone').length) {
		$('#userPhone').mask('+38 (099) 999-99-99');
	}
	//----- end plugin mask input
	// for valid form
	var validForm = new ValidForm();
	$('.forgotPassBtn').on('click', function(event) {
		$('.forgotLogin input[type="email"]').focusout();
		if ($('.forgotLogin .noValid').length > 0) {
			event.preventDefault();
		} else {
			$('.forgotLogin').hide();
			$('.loginBox').show();
		}
	});
	$('.validBtn').on('mouseenter', function() {
		$.each($(this).closest('form').find('div[required]'), function(i, item) {
			if ($(item).children('input').attr('type') === 'password' && $(item).children('input').attr('id') === 'pass') {
				$(item).children('input').focusout();
			} else {
				$(item).children('input').focusout();
			}
		});
	});
	$('.validBtn').on('click', function(event) {
		if ($('.noValid').length > 0) {
			event.preventDefault();
			return false;
		}
	});
	//----- end valid form
	// for plugin SelectInspiration
	(function() {
		[].slice.call(document.querySelectorAll('select.cs-select')).forEach(function(el) {
			new SelectFx(el);
		});
	})();
	//----- end plugin SelectInspiration
	//for bag
	var bagEdit = new BagEdit();
	$('.addNewProductBtn').on('click', function() {
		$(this).parent().siblings('.boxSize').show();
		$(this).parent().hide();

	});
	$('.cancelBtn').on('click', function() {
		$(this).parent().parent().parent().hide();
		$(this).parent().parent().parent().siblings('.boxBtn').show();

	});
	$('.itemSize').on('click', function() {
		$(this).siblings().removeClass('active');
		$(this).addClass('active');
		$('.boxInfoSize').find('.numberCount .number').text('1');
	});
	if ($(window).width() < 960) {
		$('.boxBag, .boxOrder').css({
			'left': -$('.boxBag').outerWidth()
		});
		$('.overlay').hide();
	} else {
		$('.boxBag, .boxOrder').css({
			'left': -($('.boxBag').outerWidth() - $('.rightSitebar').outerWidth())
		});
		$('.overlay').show().css({
			'left': -($('.overlay').outerWidth() - $('.rightSitebar').outerWidth())
		});
	}
	// for scrollbar
	$(".boxBag").mCustomScrollbar({ theme: "minimal" });
	//--------end bag
	//for animate add product to Bag
	$('.addProductToBag').on('click', function() {
		animateProductToBag({
			image: '#slider1 img ',
			boxBag: '.goToBag',
			opacityElem: 0.3,
			speed: 800
		});
	});
	// ----------- end animate bag
	//---- for page product
	if ($(window).width() < 960) {
		if ($('.content').hasClass('pageProduct')) {
			$('.goToMenu').css('background', 'none');
		}
	}
	$('.itemDetail').on('click', function() {
		$(this).toggleClass('active');
	});
	$('.itemSize').on('click', function() {
		$(this).siblings().removeClass('active');
		$(this).addClass('active');
	});
	// --- end page product
	if ($(window).width() < 960) {
		$("#slider1").responsiveSlides({
			auto: false,
			pager: true,
			nav: true,
			namespace: "centered-btns"
		});

	} else {
		$("#slider1").responsiveSlides({
			manualControls: '#slider1-pager',
			auto: false
		});
	}
	$('.slider').bxSlider({
		slideWidth: $('#slides2').width(),
		minSlides: 6,
		maxSlides: 6,
		slideMargin: 0,
		moveSlides: 6,
		pager: false,
		speed: 600
	});
});