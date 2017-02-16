$('.price span').remove();

var title = $('h1').text().trim();
var description = $('#tab-description p').text().trim();
var origin_url = location.href;
var origin_price = parseInt($('.price').text());
var gender = $('.breadcrumb li:eq(1)').text();
var imagesAll = [];
$('.thumbnail img').each(function() {
	var i = $(this).attr('src').replace('228x228', 'autoxauto').replace('90x90', 'autoxauto');
	imagesAll.push(i);
});
var images = JSON.stringify(imagesAll);
var data = new FormData();


data.append( 'title', title  );
data.append( 'description', description  );
data.append( 'origin_url', origin_url  );
data.append( 'origin_price', origin_price  );
data.append( 'gender', gender  );
data.append( 'images', images  );

$.ajax({
    url: 'http://ezi.co.ua/php.php',
    data: data,
    processData: false,
    contentType: false,
    type: 'POST',
    success: function ( data ) { console.log( data );}
});