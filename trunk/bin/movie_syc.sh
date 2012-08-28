#!/bin/bash
#Run Every Hour at :10

function usage {
	echo "Usage: movie_syc.sh ENV"
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

TYPE="mov"

mysql --skip-column-names --raw -u $MyUSER -p$MyPASS -h$MyHOST -D$MyDB -e'select `fk_film_id`,`fk_user_id`, `file_encode_size`, `file_encode_source` from `file_encode` where `file_encode_id` IN (select max(`file_encode_id`) from `file_encode` group by `fk_film_id`, `file_encode_size`) and `file_encode_status` = 2 order by `file_encode_date` desc;'  | while read FILM USER SIZE SOURCE; do
  # use $theme_name and $guid variables

	echo "Sync Movies"
	echo 'rsync -av /var/www/html/sites/upload/'$USER'/'$FILM'/movie-'$SIZE'.'$TYPE' sshacs@ctvhd.upload.akamai.com:/113663/movies/'$FILM
	rsync -av /var/www/html/sites/upload/$USER/$FILM/movie-$SIZE.$TYPE sshacs@ctvhd.upload.akamai.com:/113663/movies/$FILM/
mysql --skip-column-names -u$MyUSER -p$MyPASS -h$MyHOST -D$MyDB <<EOF
insert into file_encode (fk_film_id, fk_user_id, file_encode_source, file_encode_size, file_encode_date, file_encode_status) values ( $FILM, '$USER', '$SOURCE', '$SIZE', now(), 3 );
EOF

done

exit 0
