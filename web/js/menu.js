function MainPages(obj) {
	this.variables(obj);
	this.events();
	this.mainMenuPosition();
}

MainPages.prototype.variables = function(obj) {
	this.wrapper = obj.wrapper;
	this.menu = obj.menu;
	this.rightSitebar = obj.rightSitebar;
	this.linkMainMenu = obj.linkMainMenu;
	this.goToMenu = obj.goToMenu;
	this.wrappMenu = obj.wrappMenu;
	this.content = obj.content;
	this.linkMenu = obj.linkMenu;
	this.submenu = obj.submenu;
	this.linkSubmenu = obj.linkSubmenu;
	this.checkLang = obj.checkLang;
	this.activeLang = obj.activeLang;
	this.lang1 = obj.lang1;
	this.social = obj.social;
};

MainPages.prototype.events = function() {
	var that = this;
	$(window).on('resize', function() {
		that.mainMenuPosition(this);
	});
	$(this.linkMainMenu).on('click', function() {
		that.showMenu(this);
	});

	$(this.linkMenu).on('click', function() {
		that.showSubMenu(this);
	});

	$(this.lang1).on('click', function() {
		that.checkLanguage(this);
	});
};

MainPages.prototype.showMenu = function(that) {
	if ($(that).parent().siblings().find('.goToBag').hasClass('openMenu')) {
		$(that).parent().siblings().find('.goToBag').click();
	}

	if ($(that).hasClass(this.goToMenu.replace('.', ''))) {

		if (!$(that).hasClass('openMenu')) {
			$(that).addClass('openMenu');
			$('.overlay').css('left', 0).show();

			if ($(window).width() > 959) {
				$(this.wrappMenu).css({
					'display': 'table'
				}).stop().animate({
					left: $('.rightSitebar').outerWidth()
				}, 300, function() {
					$(this.wrappMenu).css('zIndex', '5');
				}.bind(this));

				$(this.content).stop().animate({
					marginRight: -$(this.wrappMenu).outerWidth()
				}, 300);
			} else {
				$(this.wrappMenu).css({
					'display': 'table',
				}).stop().animate({
					left: '0'
				}, 300);
				$(this.content).stop().animate({
					marginRight: -$(this.wrappMenu).outerWidth()
				}, 300, function() {
					$(this.wrappMenu).css('zIndex', '2');
				}.bind(this));
				$(this.rightSitebar).stop().animate({
					left: $(this.wrappMenu).outerWidth()
				}, 300);
			}
		} else {
			this.hideMenu(that);
		}
	}
};

MainPages.prototype.hideMenu = function(that) {
	$(this.goToMenu).removeClass('openMenu');
	$('.overlay').css('left', '-100%').hide();

	if ($(window).width() > 959) {
		$(this.wrappMenu).css('zIndex', '-1').stop().animate({
			left: $('.rightSitebar').outerWidth() - $(this.wrappMenu).outerWidth(),
		}, 200, function() {
			$(this.wrappMenu).hide();
		});

		$(this.content).stop().animate({
			marginRight: 0
		}, 200);
	} else {
		$(this.content).stop().animate({
			marginRight: 0
		}, 200, function() {
			$(this.wrappMenu).css('zIndex', '2');
		}.bind(this));
		$(this.rightSitebar).stop().animate({
			left: 0
		}, 200);

		$(this.wrappMenu).css('zIndex', '-1').stop().animate({
			left: -$(this.wrappMenu).outerWidth(),
		}, 200, function() {
			$(this.wrappMenu).hide();
		});
	}
};

MainPages.prototype.showSubMenu = function(that) {
	if (!$(that).hasClass(this.linkSubmenu.replace('.', '')) && $(that).parent().find(this.submenu).length > 0) {

		if ($(that).parent().parent().find('.openSubmenu').length && $(that).parent().parent().find('.activeSubmenu').length) {

			if ($(that).hasClass('openSubmenu') && $(that).hasClass('openSubmenu')) {
				$(that).removeClass('openSubmenu');
				$(that).parent().find(this.submenu).removeClass('activeSubmenu').hide();
			} else {
				$(that).parent().parent().find('.openSubmenu').removeClass('openSubmenu');
				$(that).parent().parent().find('.activeSubmenu').removeClass('activeSubmenu').hide();
				$(that).addClass('openSubmenu');
				$(that).parent().find(this.submenu).addClass('activeSubmenu').show();
			}
		} else {
			$(that).addClass('openSubmenu');
			$(that).parent().find(this.submenu).addClass('activeSubmenu').show();
		}
	} else {
		$(that).closest(this.menu).find('.activeMenuItem').removeClass('activeMenuItem');
		$(that).addClass('activeMenuItem');
		this.hideMenu(that);
	}
};

MainPages.prototype.mainMenuPosition = function(that) {

	if ($(window).width() <= 959) {

		if ($(window).width() > $(window).height()) {
			$('body').addClass('horizontalMob');
		} else {
			$('body').removeClass('horizontalMob');
		}

		if ($(this.goToMenu).hasClass('openMenu')) {
			$(this.wrappMenu).css({
				'zIndex': '3005',
				'left': 0
			});

			$(this.content).css({
				marginRight: -$(this.wrappMenu).outerWidth()
			});

			$(this.rightSitebar).css({
				left: $(this.wrappMenu).outerWidth()
			});

		} else {
			$(this.wrappMenu).css({
				'zIndex': '-1',
				'left': -$(this.wrappMenu).outerWidth()
			});

			$(this.content).css({
				marginRight: 0
			});

			$(this.rightSitebar).css({
				left: 0
			});
		}

	} else {
		$(this.rightSitebar).css({
			'left': 0
		});
		if ($(this.goToMenu).hasClass('openMenu')) {
			$(this.wrappMenu).css({
				'zIndex': '3005',
				'left': $('.rightSitebar').outerWidth()
			});

			$(this.content).css({
				marginRight: -$(this.wrappMenu).outerWidth()
			});
		} else {
			$(this.wrappMenu).css({
				'zIndex': '-1',
				'left': -($(this.wrappMenu).outerWidth() - $('.rightSitebar').outerWidth())
			});

			$(this.content).css({
				marginRight: 0
			});
		}
	}
};

MainPages.prototype.checkLanguage = function(that) {
	$(that).closest(this.checkLang).find(this.activeLang).removeClass(this.activeLang.replace('.', ''));
	$(that).addClass(this.activeLang.replace('.', ''));
};