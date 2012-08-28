#!/bin/bash

function usage {
	echo "Usage: movie_end.sh ENV"
	exit 0
}

if [ -z "$1" ]
then
  usage
else
  ENV=$1
fi

if [ "$ENV" == "dev" ];
then
	MyUSER=root
	MyPASS=1hsvy5qb
	MyHOST=192.168.2.7
	MyDB=constellation_dev
elif [ "$ENV" == "stage" ]; then 
	MyUSER=root
	MyPASS=constellation2010
	MyHOST=localhost
	MyDB=constellation_stage
elif [ "$ENV" == "test" ]; then
	MyUSER=root
	MyPASS=constellation2010
	MyHOST=10.116.182.195
	MyDB=constellation_test
elif [ "$ENV" == "prod" ]; then 
	MyUSER=root
	MyPASS=constellation2010
	MyHOST=db.constellation.tv
	MyDB=constellation
else
	usage
fi

TYPE='m4v'

mysql --skip-column-names --raw -u $MyUSER -p$MyPASS -h$MyHOST -D$MyDB -e'SELECT `file_upload_id`, `file_upload_user`, `file_upload_film`, `file_upload_filename` FROM `file_upload` WHERE `file_upload_status`="1" limit 1;'  | while read FID USER ID SOURCE; do
    # use $theme_name and $guid variables

	echo "Encoding Movies"
	INPUTFILE=/var/www/html/sites/upload/$USER/$ID/$SOURCE
	
	BUSY=`lsof "$INPUTFILE" 2>&1 | awk '/vsftpd/ {print $2}'`
	if [ -z "$BUSY" ]; then
	
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'largest', now(), 0 );
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'large', now(), 0 );
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'medium', now(), 0 );
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'small', now(), 0 );
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'low', now(), 0 );
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'minimum', now(), 0 );
EOF

#mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
#update file_upload set file_upload_status = 2 where file_upload_id = $FID;
#EOF
	
	SAR=`ffprobe -i $INPUTFILE 2>&1 | awk '/SAR/ {print $13}'`
	if [ "$SAR" == "64:45" ]; 
	then
		echo "SAR 64:45 Detected, Correcting"
		CORRECT="-s 1280x720 -vf setdar=16:9 -sar 16:9 -aspect 16:9"
	elif [ "$SAR" == "53:45" ]; 
	then
		echo "SAR 83:45 Detected, Correcting"
		CORRECT="-s 1280x720 -vf setdar=16:9 -sar 16:9 -aspect 16:9"
	else
		CORRECT=""
	fi
		
	###Largest Version
	CHKFILE=/var/www/html/sites/upload/$USER/$ID/.movie-largest-encode
	if [ ! -e $CHKFILE ]
	then
		#-sar 16:9 -aspect 16:9
		OUTFILE=/var/www/html/sites/upload/$USER/$ID/movie-largest.$TYPE
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'largest', now(), 1 );
EOF
		ffmpeg -i $INPUTFILE -y -c:v libx264 -c:a libfaac -ac 2 -ar 44100 -ab 196k -r 30 -vb 2600k -bufsize 2000k -g 60 $CORRECT $OUTFILE
		RC=$?
		if [ "${RC}" -ne "0" ]; then
		    # Do something to handle the error.
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'largest', now(), -1 );
EOF
		else
		    # Everything was ok.
		    echo date > $CHKFILE
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'largest', now(), 2 );
EOF
		fi
	fi
	
	###Large Version
	CHKFILE=/var/www/html/sites/upload/$USER/$ID/.movie-large-encode
	if [ ! -e $CHKFILE ]
	then
		#-sar 16:9 -aspect 16:9
		OUTFILE=/var/www/html/sites/upload/$USER/$ID/movie-large.$TYPE
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'large', now(), 1 );
EOF
		ffmpeg -i $INPUTFILE -y -c:v libx264 -c:a libfaac -ac 2 -ar 44100 -ab 196k -r 30 -vb 2000k -bufsize 2000k -g 60 $CORRECT $OUTFILE
		RC=$?
		if [ "${RC}" -ne "0" ]; then
		    # Do something to handle the error.
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'large', now(), -1 );
EOF
		else
		    # Everything was ok.
		    echo date > $CHKFILE
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'large', now(), 2 );
EOF
		fi
	fi
	
	###Medium Version
	CHKFILE=/var/www/html/sites/upload/$USER/$ID/.movie-medium-encode
	if [ ! -e $CHKFILE ]
	then
		#-sar 16:9 -aspect 16:9
		OUTFILE=/var/www/html/sites/upload/$USER/$ID/movie-medium.$TYPE
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'medium', now(), 1 );
EOF
		ffmpeg -i $INPUTFILE -y -c:v libx264 -c:a libfaac -ac 2 -ar 44100 -ab 196k -r 30 -vb 1200k -bufsize 2000k -g 60 $CORRECT $OUTFILE
		RC=$?
		if [ "${RC}" -ne "0" ]; then
		    # Do something to handle the error.
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'medium', now(), -1 );
EOF
		else
		    # Everything was ok.
		    echo date > $CHKFILE
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'medium', now(), 2 );
EOF
		fi
	fi
	
	###Small Version
	CHKFILE=/var/www/html/sites/upload/$USER/$ID/.movie-small-encode
	if [ ! -e $CHKFILE ]
	then
		#-sar 16:9 -aspect 16:9
		OUTFILE=/var/www/html/sites/upload/$USER/$ID/movie-small.$TYPE
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'small', now(), 1 );
EOF
		ffmpeg -i $INPUTFILE -y -c:v libx264 -c:a libfaac -ac 2 -ar 44100 -ab 196k -r 30 -vb 800k -bufsize 2000k -g 60 $CORRECT $OUTFILE
		RC=$?
		if [ "${RC}" -ne "0" ]; then
		    # Do something to handle the error.
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'small', now(), -1 );
EOF
		else
		    # Everything was ok.
		    echo date > $CHKFILE
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'small', now(), 2 );
EOF
		fi
	fi
	
	###Low Version
	CHKFILE=/var/www/html/sites/upload/$USER/$ID/.movie-low-encode
	if [ ! -e $CHKFILE ]
	then
		#-sar 16:9 -aspect 16:9
		OUTFILE=/var/www/html/sites/upload/$USER/$ID/movie-low.$TYPE
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'low', now(), 1 );
EOF
		ffmpeg -i $INPUTFILE -y -c:v libx264 -c:a libfaac -ac 2 -ar 44100 -ab 196k -r 30 -vb 500k -bufsize 2000k -g 60 $CORRECT $OUTFILE
		RC=$?
		if [ "${RC}" -ne "0" ]; then
		    # Do something to handle the error.
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'low', now(), -1 );
EOF
		else
		    # Everything was ok.
		    echo date > $CHKFILE
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'low', now(), 2 );
EOF
		fi
	fi
	
	###Minimum Version
	CHKFILE=/var/www/html/sites/upload/$USER/$ID/.movie-minimum-encode
	if [ ! -e $CHKFILE ]
	then
		#-sar 16:9 -aspect 16:9
		OUTFILE=/var/www/html/sites/upload/$USER/$ID/movie-minimum.$TYPE
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'minimum', now(), 1 );
EOF
		ffmpeg -i $INPUTFILE -y -c:v libx264 -c:a libfaac -ac 2 -ar 44100 -ab 196k -r 30 -vb 300k -bufsize 2000k -g 60 $CORRECT $OUTFILE
		RC=$?
		if [ "${RC}" -ne "0" ]; then
		    # Do something to handle the error.
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'minimum', now(), -1 );
EOF
		else
		    # Everything was ok.
		    cp $OUTFILE  /var/www/html/sites/upload/$USER/$ID/movie-min.mov
		    echo date > $CHKFILE
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'min', now(), 2 );
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $ID, '$USER', '$SOURCE', 'minimum', now(), 2 );
EOF
		fi
	fi
	fi
	
done

exit 0
