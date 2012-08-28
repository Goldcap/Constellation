#!/bin/sh

##TTJ GRAIL CSS
echo -en '\E[40;32m'
echo "CREATING CONSTELLATION CSS"
tput sgr0 

echo "/********** START MAIN CSS ***************/" > constellation.css
cat main_styles.css >> constellation.css
echo "/********** END MAIN CSS ***************/" >> constellation.css
echo "/********** START SCROLLPANE CSS ***************/" >> constellation.css
cat ../js/jquery/vitch-jScrollPane-76f0071/style/jquery.jscrollpane.css >> constellation.css
echo "/********** END SCROLLPANE CSS ***************/" >> constellation.css
echo "/********** START CAROUSEL CSS ***************/" >> constellation.css
cat carousel.css >> constellation.css
echo "/********** END CAROUSEL CSS ***************/" >> constellation.css
echo "/********** START JQUERY UI CSS ***************/" >> constellation.css
cat ../js/jquery/ui/themes/smoothness/jquery-ui-1.8.1.custom.css >> constellation.css
echo "/********** END JQUERY UI CSS ***************/" >> constellation.css
echo "/********** START FANCYBOX CSS ***************/" >> constellation.css
cat ../js/jquery/jquery.fancybox-1.3.1/fancybox/jquery.fancybox-1.3.1.css >> constellation.css
echo "/********** END FANCYBOX CSS ***************/" >> constellation.css
echo "/********** START TABS CSS ***************/" >> constellation.css
cat tabs.css >> constellation.css
echo "/********** END TABS CSS ***************/" >> constellation.css
echo "/********** START THEATER SIDE CSS ***************/" >> constellation.css
cat feedback.css >> constellation.css
echo "/********** END TABS CSS ***************/" >> constellation.css
#echo "/********** START THEATER SIDE CSS ***************/" >> constellation.css
#cat theater_side.css >> constellation.css
#echo "/********** END THEATER SIDE CSS ***************/" >> constellation.css
echo "/********** START REVIEWS CSS ***************/" >> constellation.css
cat reviews.css >> constellation.css
echo "/********** END REVIEWS CSS ***************/" >> constellation.css

echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
gzip constellation.css -c > constellation.css.gz

echo -en '\E[40;34m'
echo "APPLYING SSL, AND COMPRESSING..."
sed -e 's/http/https/g' constellation.css > constellation_ssl.css
tput sgr0 
gzip constellation_ssl.css -c > constellation_ssl.css.gz



echo "/********** START MAIN CSS ***************/" > constellation_alt.css
cat main_styles.css >> constellation_alt.css
echo "/********** END MAIN CSS ***************/" >> constellation_alt.css
echo "/********** START MAIN ALT CSS ***************/" >> constellation_alt.css
cat main_styles_alt.css >> constellation_alt.css
echo "/********** END MAIN ALT CSS ***************/" >> constellation_alt.css
echo "/********** START SCROLLPANE CSS ***************/" >> constellation_alt.css
cat ../js/jquery/vitch-jScrollPane-76f0071/style/jquery.jscrollpane.css >> constellation_alt.css
echo "/********** END SCROLLPANE CSS ***************/" >> constellation_alt.css
echo "/********** START CAROUSEL CSS ***************/" >> constellation_alt.css
cat carousel.css >> constellation_alt.css
echo "/********** END CAROUSEL CSS ***************/" >> constellation_alt.css
echo "/********** START JQUERY UI CSS ***************/" >> constellation_alt.css
cat ../js/jquery/ui/themes/smoothness/jquery-ui-1.8.1.custom.css >> constellation_alt.css
echo "/********** END JQUERY UI CSS ***************/" >> constellation_alt.css
echo "/********** START FANCYBOX CSS ***************/" >> constellation_alt.css
cat ../js/jquery/jquery.fancybox-1.3.1/fancybox/jquery.fancybox-1.3.1.css >> constellation_alt.css
echo "/********** END FANCYBOX CSS ***************/" >> constellation_alt.css
echo "/********** START TABS CSS ***************/" >> constellation_alt.css
cat tabs.css >> constellation_alt.css
echo "/********** END TABS CSS ***************/" >> constellation_alt.css
echo "/********** START THEATER SIDE CSS ***************/" >> constellation_alt.css
cat feedback.css >> constellation_alt.css
echo "/********** END TABS CSS ***************/" >> constellation_alt.css
echo "/********** START THEATER SIDE CSS ***************/" >> constellation_alt.css
cat theater_side.css >> constellation_alt.css
echo "/********** END THEATER SIDE CSS ***************/" >> constellation_alt.css
echo "/********** START REVIEWS CSS ***************/" >> constellation_alt.css
cat reviews.css >> constellation_alt.css
echo "/********** END REVIEWS CSS ***************/" >> constellation_alt.css


echo -en '\E[40;34m'
echo "COMPRESSING..."
tput sgr0 
gzip constellation_alt.css -c > constellation_alt.css.gz

echo -en '\E[40;34m'
echo "APPLYING SSL, AND COMPRESSING..."
sed -e 's/http/https/g' constellation_alt.css > constellation_alt_ssl.css
tput sgr0 
gzip constellation_alt_ssl.css -c > constellation_alt_ssl.css.gz

echo -en '\E[40;36m'
echo "DONE WITH CONSTELLATION CSS"
tput sgr0 

exit 0
