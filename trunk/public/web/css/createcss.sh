#!/bin/sh

##TTJ GRAIL CSS
echo -en '\E[40;32m'
echo "CREATING CONSTELLATION CSS"
tput sgr0 

echo "/********** START RESET CSS ***************/" > constellation.css
cat reset.css >> constellation.css
echo "/********** END RESET CSS ***************/" >> constellation.css
#echo "/********** START FONT CSS ***************/" >> constellation.css
#cat font.css >> constellation.css
#echo "/********** END FONT CSS ***************/" >> constellation.css
echo "/********** START TYPOGRAPHY CSS ***************/" >> constellation.css
cat typography.css >> constellation.css
echo "/********** END TYPOGRAPHY CSS ***************/" >> constellation.css
echo "/********** START BUTTONS CSS ***************/" >> constellation.css
cat buttons.css >> constellation.css
echo "/********** END BUTTONS CSS ***************/" >> constellation.css
echo "/********** START BASE CSS ***************/" >> constellation.css
cat base.css >> constellation.css
echo "/********** END BASE CSS ***************/" >> constellation.css
echo "/********** START HOME CSS ***************/" >> constellation.css
cat form.css >> constellation.css
echo "/********** END BASE CSS ***************/" >> constellation.css
echo "/********** START HOME CSS ***************/" >> constellation.css
cat home.css >> constellation.css
echo "/********** END HOME CSS ***************/" >> constellation.css
echo "/********** START ALL FILMS CSS ***************/" >> constellation.css
cat all_films.css >> constellation.css
echo "/********** END ALL FILMS CSS ***************/" >> constellation.css
echo "/********** START ABOUT CSS ***************/" >> constellation.css
cat about.css >> constellation.css
echo "/********** END ABOUT CSS ***************/" >> constellation.css
echo "/********** START HOST SHOW CSS ***************/" >> constellation.css
cat host_show.css >> constellation.css
echo "/********** END HOST SHOW CSS ***************/" >> constellation.css
echo "/********** START FILM CSS ***************/" >> constellation.css
cat film.css >> constellation.css
echo "/********** END FILM CSS ***************/" >> constellation.css
echo "/********** START LIGHTBOX CSS ***************/" >> constellation.css
cat lightbox.css >> constellation.css
echo "/********** END LIGHTBOX CSS ***************/" >> constellation.css
echo "/********** START ACCOUNT EDIT CSS ***************/" >> constellation.css
cat account_edit.css >> constellation.css
echo "/********** END ACCOUNT EDIT CSS ***************/" >> constellation.css
echo "/********** START SMOOTHNESS CSS ***************/" >> constellation.css
cat ../js/jquery/ui/themes/smoothness/jquery-ui-1.8.1.custom.css >> constellation.css
echo "/********** END SMOOTHNESS CSS ***************/" >> constellation.css
echo "/********** START FANCYBOX CSS ***************/" >> constellation.css
cat ../js/jquery/jquery.fancybox-1.3.1/fancybox/jquery.fancybox-1.3.1.css >> constellation.css
echo "/********** END FANCYBOX CSS ***************/" >> constellation.css
echo "/********** START THEATER SIDE CSS ***************/" >> constellation.css
cat theater_side.css >> constellation.css
echo "/********** END THEATER SIDE CSS ***************/" >> constellation.css
echo "/********** START FACEBOOK SHARE CSS ***************/" >> constellation.css
cat facebook_share.css >> constellation.css
echo "/********** END FACEBOOK SHARE CSS ***************/" >> constellation.css
echo "/********** START CONVERSATION CSS ***************/" >> constellation.css
cat conversation.css >> constellation.css
echo "/********** END CONVERSATION CSS ***************/" >> constellation.css

echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
gzip constellation.css -c > constellation.css.gz
cp constellation.css.gz -c constellation_gz.css

echo -en '\E[40;34m'
echo "APPLYING SSL, AND COMPRESSING..."
sed -e 's/http:/https:/g' constellation.css > constellation_ssl.css 
tput sgr0 
gzip constellation_ssl.css -c > constellation_ssl.css.gz
cp constellation_ssl.css.gz -c constellation_ssl_gz.css

exit 0
