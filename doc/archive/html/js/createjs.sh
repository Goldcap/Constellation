#!/bin/sh

function usage {
  echo "Usage: create.sh [VERSION]"
  exit 0
}

if [ -z "$1" ];
then
  usage
else
  VERSION=$1
fi

##Constellation JS
echo -en '\E[40;32m'
echo "CREATING CONSTELLATION JS"
tput sgr0 

echo -en '\E[40;32m'
#echo "Adding JQUERY Base"
#cat jquery/jquery-1.4.2.min.js > constellation.js
cat utils.js > constellation.js
cat jquery/ui/jquery-ui-1.8.4.custom.min.js >> constellation.js
cat jquery/jquery.cookie.js >> constellation.js
cat jquery/jquery.blockUI-2.33.min.js >> constellation.js
cat jquery/jquery.watermark-3.0.5.min.js >> constellation.js
cat jquery/fancybox-1.2.6/jquery.fancybox.min.js >> constellation.js
cat jquery/vitch-jScrollPane-76f0071/script/jquery.mousewheel.js >> constellation.js
cat jquery/vitch-jScrollPane-76f0071/script/jquery.jscrollpane.js >> constellation.js
cat error.js >> constellation.js
cat swfobject.js >> constellation.js
#cat videoplayer.js >> constellation.js
cat keyevent.js >> constellation.js
#cat login.js >> constellation.js
cat trailer.js >> constellation.js
cat jquery/jquery.countdown/jquery.countdown.js >> constellation.js
cat countdown.js >> constellation.js

echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
java -jar ../../../tools/yuicompressor-2.4.2.jar constellation.js -o constellation_comp.js
cp constellation_comp.js constellation_comp_v$VERSION.js


##Constellation JS
echo -en '\E[40;32m'
echo "CREATING CONSTELLATION ALT JS"
tput sgr0 
#echo "Adding JQUERY ALT Base"
cat utils.js > constellation_alt.js
#cat tiny_mce/tiny_mce.js >> constellation_alt.js
cat jquery/fancybox-1.2.6/jquery.fancybox.min.js >> constellation_alt.js
cat jquery/ui/jquery-ui-1.8.4.custom.min.js >> constellation_alt.js
cat jquery/jquery.cookie.js >> constellation_alt.js
cat jquery/jquery.tools.min.js >> constellation_alt.js
cat jquery/jquery.blockUI-2.33.min.js >> constellation_alt.js
cat jquery/jquery.watermark-3.0.5.min.js >> constellation_alt.js
cat jquery/jquery-asyncUpload-0.1.js >> constellation_alt.js
cat jquery/vitch-jScrollPane-76f0071/script/jquery.mousewheel.js >> constellation_alt.js
cat jquery/vitch-jScrollPane-76f0071/script/jquery.jscrollpane.js >> constellation_alt.js
cat jquery/jquery.countdown/jquery.countdown.js >> constellation_alt.js
cat error_alt.js >> constellation_alt.js
cat swfobject.js >> constellation_alt.js
cat swfupload/swfupload.js >> constellation_alt.js
cat keyevent.js >> constellation_alt.js
cat how.js >> constellation_alt.js
#cat trailer_alt.js >> constellation_alt.js
#cat login_alt.js >> constellation_alt.js
#cat countdown_alt.js >> constellation_alt.js
cat timezones.js >> constellation_alt.js

echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
java -jar ../../../tools/yuicompressor-2.4.2.jar constellation_alt.js -o constellation_alt_comp.js
cp constellation_alt_comp.js constellation_alt_comp_v$VERSION.js

echo -en '\E[40;32m'
echo "Creating Thslide"
tput sgr0 
cat jquery/Thslide/jquery.barousel.js > thslide.js
cat jquery/Thslide/jquery.thslide.js >> thslide.js

echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
java -jar ../../../tools/yuicompressor-2.4.2.jar thslide.js -o thslide_comp.js

echo -en '\E[40;32m'
echo "Creating Homepage"
tput sgr0 
cat jquery/Thslide/jquery.barousel.js > constellation_home.js
cat jquery/Thslide/jquery.thslide.js >> constellation_home.js
cat jquery/jquery.jclock.js >> constellation_home.js
cat jquery/jquery.carousel/lib/jquery.jcarousel.min.js >> constellation_home.js
cat jquery/jquery.carousel/reflection.js >> constellation_home.js
cat carousel.js >> constellation_home.js
cat screenings_carousel.js >> constellation_home.js
cat screening_list.js >> constellation_home.js
cat genre_search.js >> constellation_home.js
cat timezones.js >> constellation_home.js
cat upcoming.js >> constellation_home.js
cat feedback.js >> constellation_home.js

echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
java -jar ../../../tools/yuicompressor-2.4.2.jar constellation_home.js -o constellation_home_comp.js
cp constellation_home_comp.js constellation_home_comp_v$VERSION.js


echo -en '\E[40;32m'
echo "Creating Filmpage"
tput sgr0 
cat tiny_mce/tiny_mce.js > constellation_film.js
cat swfupload/swfupload.js >> constellation_film.js
cat jquery/fancybox-1.2.6/jquery.fancybox.min.js >> constellation_film.js
cat jquery/Thslide/jquery.barousel.js > constellation_film.js
cat jquery/Thslide/jquery.thslide.js >> constellation_film.js
cat jquery/jquery.jclock.js >> constellation_film.js
cat jquery/jquery.cookie.js >> constellation_film.js
cat jquery/jquery.jeditable.mini.js >> constellation_film.js
cat jquery/jquery.inputlimiter.1.3.2.min.js >> constellation_film.js
cat jquery/jquery.ajax_head.js >> constellation_film.js
cat jquery/jQuery-Timepicker-Addon/jquery-ui-timepicker-addon.js >> constellation_film.js
cat jquery/jquery-asyncUpload-0.1.js >> constellation_film.js
cat screenings_carousel.js >> constellation_film.js
cat screening_list.js >> constellation_film.js
cat history.js >> constellation_film.js
cat timezones.js >> constellation_film.js
#cat purchase.js >> constellation_film.js
cat host.js >> constellation_film.js
cat reviews.js >> constellation_film.js
cat feedback.js >> constellation_film.js

echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
java -jar ../../../tools/yuicompressor-2.4.2.jar constellation_film.js -o constellation_film_comp.js
cp constellation_film_comp.js constellation_film_comp_v$VERSION.js

echo -en '\E[40;32m'
echo "Creating Sponsor"
tput sgr0 
#cat tiny_mce/tiny_mce.js > constellation_sponsor.js
cat jquery/Thslide/jquery.barousel.js > constellation_sponsor.js
cat jquery/Thslide/jquery.thslide.js >> constellation_sponsor.js
#cat swfupload/swfupload.js >> constellation_sponsor.js
#cat jquery/fancybox-1.2.6/jquery.fancybox.min.js >> constellation_sponsor.js
cat jquery/jquery.jclock.js >> constellation_sponsor.js
cat jquery/jquery.cookie.js >> constellation_sponsor.js
cat jquery/jquery.jeditable.mini.js >> constellation_sponsor.js
cat jquery/jquery.inputlimiter.1.2.1.min.js >> constellation_sponsor.js
cat jquery/jquery.ajax_head.js >> constellation_sponsor.js
cat jquery/jQuery-Timepicker-Addon/jquery-ui-timepicker-addon.js >> constellation_sponsor.js
#cat jquery/jquery-asyncUpload-0.1.js >> constellation_sponsor.js
cat screenings_carousel.js >> constellation_sponsor.js
cat screening_list.js >> constellation_sponsor.js
cat history.js >> constellation_sponsor.js
cat timezones.js >> constellation_sponsor.js
cat feedback.js >> constellation_sponsor.js
#cat sponsor.js >> constellation_sponsor.js

echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
java -jar ../../../tools/yuicompressor-2.4.2.jar constellation_sponsor.js -o constellation_sponsor_comp.js

cp constellation_sponsor_comp.js constellation_sponsor_comp_v$VERSION.js

echo -en '\E[40;32m'
echo "Creating Facebook"
tput sgr0 

cat constellation_alt_comp.js > constellation_facebook.js

echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
java -jar ../../../tools/yuicompressor-2.4.2.jar constellation_facebook.js -o constellation_facebook_comp.js

cp constellation_facebook_comp.js constellation_facebook_comp_v$VERSION.js

  
echo -en '\E[40;32m'
echo "Creating Theater"
tput sgr0 
#cat tiny_mce/tiny_mce.js > constellation_film.js
cat jquery/jquery.cycle/jquery.cycle.lite.min.js > constellation_theater.js
cat jquery/jquery.ajax_head.js >> constellation_theater.js
cat jquery/jquery.jeditable.mini.js >> constellation_theater.js
cat jquery/jquery.inputlimiter.1.2.1.min.js >> constellation_theater.js
#cat timekeeper.js >> constellation_theater.js
#cat invite.js >> constellation_theater.js
cat activity.js >> constellation_theater.js
#cat chat.js >> constellation_theater.js
#cat adminmessage.js >> constellation_theater.js
#cat recommend.js >> constellation_theater.js
#cat promotions.js >> constellation_theater.js
#cat qanda.js >> constellation_theater.js
#cat toolbar.js >> constellation_theater.js
#cat qanda_videoplayer.js >> constellation_theater.js
#Removed From Theater
#cat private.js >> constellation_theater.js

echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
java -jar ../../../tools/yuicompressor-2.4.2.jar constellation_theater.js -o constellation_theater_comp.js

cp constellation_theater_comp.js constellation_theater_comp_v$VERSION.js

echo -en '\E[40;32m'
echo "Creating Lobby"
tput sgr0 
cat jquery/jquery.ajax_head.js > constellation_lobby.js
cat jquery/jquery.cookie.js >> constellation_lobby.js
cat activity.js >> constellation_lobby.js
#cat adminmessage.js >> constellation_lobby.js

echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
java -jar ../../../tools/yuicompressor-2.4.2.jar constellation_lobby.js -o constellation_lobby_comp.js

cp constellation_lobby_comp.js constellation_lobby_comp_v$VERSION.js

echo -en '\E[40;36m'
echo "DONE WITH CONSTELLATION JS"
tput sgr0 

exit 0
