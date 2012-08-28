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

gzip -c --best "jquery/jquery-1.6.4.min.js" > "jquery/jquery-1.6.4.min.js.gz"
cp  jquery/jquery-1.6.4.min.js.gz jquery/jquery-1.6.4.min_gz.js

##Constellation JS
echo -en '\E[40;32m'
echo "CREATING CONSTELLATION JS"
tput sgr0 

echo -en '\E[40;32m'

cat utils.js > constellation.js
cat underscore.js >> constellation.js
cat jquery/ui/jquery-ui-1.8.4.custom.min.js >> constellation.js
cat jquery/jquery.cookie.js >> constellation.js 
cat jquery/jquery.tools.min.js >> constellation.js
cat jquery/jquery.blockUI-2.33.min.js >> constellation.js
cat jquery/jquery.watermark-3.0.5.min.js >> constellation.js
cat jquery/jquery.fancybox-1.3.1/fancybox/jquery.fancybox-1.3.1.pack.js >> constellation.js
cat error_alt.js >> constellation.js >> constellation.js
cat swfobject.js >> constellation.js >> constellation.js
cat keyevent.js >> constellation.js >> constellation.js
cat modal.js >> constellation.js >> constellation.js
cat login_alt.js >> constellation.js >> constellation.js
cat bandwidth.js >> constellation.js >> constellation.js
cat jquery/jquery-asyncUpload-0.1.js >> constellation.js
cat jquery/vitch-jScrollPane-76f0071/script/jquery.mousewheel.js >> constellation.js
cat jquery/vitch-jScrollPane-76f0071/script/jquery.jscrollpane.js >> constellation.js
cat jquery/jquery.countdown/jquery.countdown.js >> constellation.js

echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
java -jar ../../../tools/yuicompressor-2.4.2.jar constellation.js -o constellation_comp.js
gzip -c --best "constellation_comp.js" > "constellation_comp.js.gz"
cp constellation_comp.js.gz constellation_comp_gz.js
cp constellation_comp.js constellation_comp_v$VERSION.js

##CONSTELLATION ALT JS
echo -en '\E[40;32m'
echo "CREATING CONSTELLATION ALT JS"
tput sgr0
 
echo -en '\E[40;32m'

cat swfupload/swfupload.js > constellation_alt.js
cat how.js >> constellation_alt.js
cat timezones.js >> constellation_alt.js
cat purchase_alt.js >> constellation_alt.js
cat jquery/jquery.dateFormat-1.0.js >> constellation_alt.js
cat ../flash/mediaplayer-5.7-licensed/jwplayer.js >> constellation_alt.js
cat trailer_alt.js >> constellation_alt.js
cat countdown_alt.js >> constellation_alt.js
cat allfilms.js >> constellation_alt.js
cat jquery/jeditable.js >> constellation_alt.js
cat jquery/jquery.inputlimiter.1.3.2.min.js >> constellation_alt.js
cat header.js >> constellation_alt.js   
cat CTV.Comments.js >> constellation_alt.js
cat CTV.Dialog.js >> constellation_alt.js
cat CTV.ShowtimeDetail.js >> constellation_alt.js

echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
java -jar ../../../tools/yuicompressor-2.4.2.jar constellation_alt.js -o constellation_alt_comp.js
gzip -c --best "constellation_alt_comp.js" > "constellation_alt_comp.js.gz"
cp constellation_alt_comp.js.gz constellation_alt_comp_gz.js
cp constellation_alt_comp.js constellation_alt_comp_v$VERSION.js

##CONSTELLATION THEATER JS
echo -en '\E[40;32m'
echo "CREATING THEATER JS"
tput sgr0

echo -en '\E[40;32m'

cat jquery/jquery.ajax_head.js > constellation_theater.js
cat jquery/jquery.jeditable.mini.js >> constellation_theater.js
cat jquery/jquery.inputlimiter.1.2.1.min.js >> constellation_theater.js
cat timekeeper.js >> constellation_theater.js
cat activity.js >> constellation_theater.js
cat qanda.js >> constellation_theater.js
cat videoplayer_v33.js >> constellation_theater.js
cat colorme.js >> constellation_theater.js
cat chat.js >> constellation_theater.js
cat hud.js >> constellation_theater.js
cat jquery/jquery.countdown/jquery.countdown.js >> constellation_theater.js
cat countdown.js >> constellation_theater.js
cat toolbar.js >> constellation_theater.js
cat fb_invite.js >> constellation_theater.js
cat invite.js >> constellation_theater.js

echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
java -jar ../../../tools/yuicompressor-2.4.2.jar constellation_theater.js -o constellation_theater_comp.js
gzip -c --best "constellation_theater_comp.js" > "constellation_theater_comp.js.gz"
cp constellation_theater_comp.js.gz constellation_theater_comp_gz.js
cp constellation_theater_comp.js constellation_theater_comp_v$VERSION.js


echo -en '\E[40;36m'
echo "DONE WITH CONSTELLATION JS"
tput sgr0 

exit 0
