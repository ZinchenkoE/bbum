function BagEdit() {
	this.events();
	this.price();
}

BagEdit.prototype.events = function() {
	var that = this;
	$('body').on('click', '.removeRowBtn', function() {
		that.removeRow(this);
	});

	$('body').on('click', '.countBtn', function() {
		that.counter(this);
	});

	$('.goToBag').on('click', function() {
		that.showHideBag(this);
	});

	$('.addBtn').on('click', function() {
		that.addNewSize(this);
	});

};

BagEdit.prototype.removeRow = function(that) {
	if ($(that).closest('.boxData').find('.boxChangeData').length === 1) {
		$(that).closest('.boxProduct').remove();
	} else {
		$(that).parent().remove();
	}

	this.price();
};

BagEdit.prototype.counter = function(that) {
	this.count = +$(that).siblings('.numberCount').find('.number').text();
	if ($(that).hasClass('prevCount')) {
		this.count--;
	} else if ($(that).hasClass('nextCount')) {
		this.count++;
		$(that).siblings('.countBtn').removeClass('noCount');
	}

	if (this.count <= 1) {
		this.count = 1;
		$(that).addClass('noCount');
	}

	$(that).siblings('.numberCount').find('.number').text(this.count);
	this.price();
};

BagEdit.prototype.price = function() {
	this.orderSum = 0;

	$.each($('.boxProduct').find('.boxPriceProduct .priceProduct'), function(i, item) {
		this.number = 0;
		this.priseProduct = +$(item).text();

		this.numberCount = $(item).closest('.boxProduct').find('.boxChangeData');

		$.each(this.numberCount, function(i, itemNum) {
			this.number += +$(itemNum).find('.numberCount .number').text();
			return this.number;
		}.bind(this));

		this.orderSum += this.priseProduct * this.number;
		return this.orderSum;
	}.bind(this));

	$.each($('.cost'), function(i, item) {
		$(item).text(this.orderSum);
	}.bind(this));

	if (this.orderSum == 0) {
		$('.boxBag .fulBag').hide();
		$('.boxBag .emptyBag').show();
	} else {
		$('.boxBag .fulBag').show();
		$('.boxBag .emptyBag').hide();
	}
};

BagEdit.prototype.showHideBag = function(that) {

	if ($(this.orderSum) == 0) {
		$('.boxBag .fulBag').hide();
		$('.boxBag .emptyBag').show();
	} else {
		$('.boxBag .fulBag').show();
		$('.boxBag .emptyBag').hide();
	}

	if ($(that).parent().siblings().find('.goToMenu').hasClass('openMenu')) {
		$(that).parent().siblings().find('.goToMenu').click();
	}

	$(that).toggleClass('openMenu');

	if ($(that).hasClass('openMenu')) {
		if ($(window).outerWidth() < 960) {
			$('.overlay').hide();
			$('.boxBag').css({
				'paddingTop': $('.rightSitebar').outerHeight(),
				'top': 0
			}).stop().animate({
				'left': 0,
				'top': 0
			}, 375);

			$('.boxOrder').stop().animate({
				'left': 0
			}, 375);

		} else {
			$('.boxBag, .overlay').stop().animate({
				'left': $('.rightSitebar').outerWidth()
			}, 375);
		}
		$('main.content').stop().animate({
			'marginRight': -$('.boxBag').outerWidth()
		}, 375, function () {
			$('main.content').hide();
		});

	} else {

		if ($(window).outerWidth() < 960) {
			$('.boxBag, .boxOrder').stop().animate({
				'left': -$('.boxBag').outerWidth()
			}, 375);

		} else {
			$('.boxBag').stop().animate({
				'left': -($('.boxBag').outerWidth() - $('.rightSitebar').outerWidth())
			}, 375);
			$('.overlay').css({
				'z-index': 0
			}).stop().animate({
				'left': -($('.overlay').outerWidth() - $('.rightSitebar').outerWidth())
			}, 375);
		}
		$('main.content').show().stop().animate({
			'marginRight': 0
		}, 375);

	}
};

BagEdit.prototype.addNewSize = function(that) {
	this.selectedSize = $(that).closest('.boxProduct').find('.changedSize');
	this.size = $(that).closest('.boxSize').find('.itemSize.active .numberSize').text();
	this.number = $(that).closest('.boxSize').find('.itemSize.active').find('.number').text();
	this.newSize = '<div class="boxChangeData"><div class="colLeft sizeProduct"><span class="changedSize">' + this.size + '</span> <span class="titleSize">размер</span></div><div class="removeRowBtn"></div><ul class="counterProd"><li class="countBtn prevCount"><span class="val"></span></li><li class="numberCount"><span class="number">' + this.number + '</span> <span class="titleNumber">пары</span></li><li class="countBtn nextCount"><span class="val"></span></li></ul></div>';
	$.each(this.selectedSize, function(i, item) {
		if (+this.size === +$(item).text()) {
			var numberParod = (+$(item).parent().siblings().find('.number').text()) + (+this.number);
			$(item).parent().siblings().find('.number').text(numberParod);
			return false;

		} else {
			$(this.newSize).appendTo($(that).closest('.boxProduct').find('.boxData'));
			return false;
		}
	}.bind(this));
	$(that).closest('.boxSize').find('.itemSize.active').find('.number').text('1');
	$(that).closest('.boxSize').hide();
	$(that).parent().parent().parent().siblings('.boxBtn').show();
	this.price();
};