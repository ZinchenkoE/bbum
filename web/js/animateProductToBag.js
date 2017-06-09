function animateProductToBag(param) {
	var copyImage = $(param.image)[0];
	var parentBoxImage = $(copyImage).parent();
	var boxBag = param.boxBag;
	var opacityElem = param.opacityElem;
	var speed = param.speed; //milisecond

	var coordsBag = $(boxBag)[0].getBoundingClientRect();
	var coordsImage = $(copyImage)[0].getBoundingClientRect();

	$('body').append($(copyImage).clone().addClass('animateToBag'));
	$('.animateToBag').css({
		'opacity': opacityElem,
		'position': 'absolute',
		'top': coordsImage.top,
		'left': coordsImage.left,
		'zIndex': 100000,
		'height': $(copyImage).height()
	}).animate({
		'height': $(boxBag).height(),
		'left': coordsBag.left,
		'top': coordsBag.top
	}, speed, function() {
		$('.animateToBag').remove();
		$(boxBag).
		console.log($(boxBag));
	});
}