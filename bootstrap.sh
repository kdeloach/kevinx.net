#!/usr/bin/env bash

apt-get update

debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password password root'
debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password_again password root'
apt-get -y install mysql-server-5.5 apache2 php5-mysql php5 php5-gd php5-curl

rm -rf /var/www
mkdir /var/www

ln -fs /vagrant/* /var/www/
ln -fs /vagrant/.* /var/www/

mkdir /var/www/kevinx.net/logs
mkdir /var/www/music.kevinx.net/logs

rm /etc/apache2/sites-enabled/000-default
ln -s /vagrant/vhosts.conf /etc/apache2/sites-enabled/vhosts.conf

# sed -i 's/display_errors = Off/display_errors = On/g' /etc/php5/apache2/php.ini

# Enable mod_rewrite
a2enmod rewrite

mysql -u root --password=root -e "create user 'kevinxn_default'@'localhost' identified by ''"
mysql -u root --password=root -e "grant all on *.* to 'kevinxn_default'@'localhost';"

mysql -u root --password=root < /vagrant/kevinxn_db.sql
mysql -u root --password=root < /vagrant/music.kevinx.net/schema.sql

service apache2 restart