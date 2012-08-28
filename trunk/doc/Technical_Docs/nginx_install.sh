#!/bin/bash

#for UBUNTU:
#http://blog.codefront.net/2007/06/11/nginx-php-and-a-php-fastcgi-daemon-init-script/

#for Fedora/Redhat
#http://www.softwareprojects.com/resources/programming/t-installing-nginx-web-server-w-php-and-ssl-1474.html
#http://www.cyberciti.biz/faq/rhel-fedora-install-configure-nginx-php5/

#http://www.mongodb.org/display/DOCS/MapReduce

yum install nginx spawn-fcgi

wget http://bash.cyberciti.biz/dl/419.sh.zip
unzip 419.sh.zip
mv 419.sh /etc/init.d/php_cgi
chmod +x /etc/init.d/php_cgi 
/etc/init.d/php_cgi start
netstat -tulpn | grep :9000

vi /etc/nginx/nginx.conf


exit 0

http://kovyrin.net/2006/05/30/nginx-php-fastcgi-howto/
http://interfacelab.com/nginx-php-fpm-apc-awesome/
