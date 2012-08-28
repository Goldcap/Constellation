#!/bin/bash
echo -t $(date +%s) 
#perl /var/www/html/sites/dev.constellation.tv/public/cgi-bin/gentoken.pl -f "/constellation/movies/4/movie-800.mp4" -r "testProfile" -p "rjr457#2" -x "dUL2xtEso9" -k "/var/www/html/sites/dev.constellation.tv/cert/testProfile_113558_etoken.bin" -w "86400" -T d
#perl /var/www/html/sites/dev.constellation.tv/public/cgi-bin/gentoken.pl -f "constellation/movies/4/movie-800.mp4" -r "testProfile" -p "rjr457#2" -x "dUL2xtEso9" -t $(date +%s) -w "86400" -T d
perl /var/www/html/sites/dev.constellation.tv/public/cgi-bin/gentoken.pl -f constellation/movies/4/movie-800 -r testProfile -p rjr457#2 -x dUL2xtEso9 -t $(date +%s) -w 86400 -T d

