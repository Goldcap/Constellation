$(document).ready(function() {

$('#accordion').accordion();

$('.manufacturers').mouseenter(function(){
  $('.bg5').css('background-image','url(i/mfr-on.jpg)');
});
$('.manufacturers').mouseleave(function(){
  $('.bg5').css('background-image','url(i/pfm-bg.jpg)');
});
$('.media').mouseenter(function(){
  $('.bg5').css('background-image','url(i/media-on.jpg)');
});
$('.media').mouseleave(function(){
  $('.bg5').css('background-image','url(i/pfm-bg.jpg)');
});
$('.resources').mouseenter(function(){
  $('.bg5').css('background-image','url(i/tools-bg.jpg)');
});
$('.resources').mouseleave(function(){
  $('.bg5').css('background-image','url(i/pfm-bg.jpg)');
});
$('.contact').mouseenter(function(){
  $('.bg5').css('background-image','url(i/contact-on.jpg)');
});
$('.contact').mouseleave(function(){
  $('.bg5').css('background-image','url(i/pfm-bg.jpg)');
});
$('.about').mouseenter(function(){
  $('.bg5').css('background-image','url(i/about-on.jpg)');
});
$('.about').mouseleave(function(){
  $('.bg5').css('background-image','url(i/pfm-bg.jpg)');
});
/*$('#mfrHover').mouseenter(function(){$('.bg5').removeClass('bg5').fadeOut('fast').addClass('mfrBg').fadeIn('fast');});
$('#mfrHover').mouseleave(function(){$('.mfrBg').removeClass('mfrBg').fadeOut('fast').addClass('bg5').fadeIn('fast');});*/

});
