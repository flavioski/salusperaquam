#!/bin/sh

# copy php.ini file
cp /tmp/.docker/php7.4/php.ini /usr/local/etc/php/php.ini

# copy cacert.pem
cp /tmp/.docker/certs/cacert-221011.pem /usr/local/share/ca-certificates/cacert.pem

# copy new default apache2 file
cp /tmp/.docker/apache2/apache2.conf /etc/apache2/apache2.conf

# copy new default site config apache2 file
cp /tmp/.docker/apache2/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default.conf

# copy selfsigned certificate for default site
cp /tmp/.docker/certs/selfsigned.crt /usr/local/share/ca-certificates/selfsigned.crt
cp /tmp/.docker/certs/selfsigned.key /usr/local/share/ca-certificates/selfsigned.key

# enable ssl
a2enmod ssl

# reload apache
#/etc/init.d/apache2 reload

# restart apache
#service apache2 restart
