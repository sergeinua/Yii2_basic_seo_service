sudo add-apt-repository "deb http://archive.ubuntu.com/ubuntu $(lsb_release -sc) main"
sudo add-apt-repository "deb http://archive.ubuntu.com/ubuntu $(lsb_release -sc) universe"
sudo apt-get -y update
sudo apt-get install -y apache2

sudo debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password password 12345'
sudo debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password_again password 12345'

sudo apt-get -y install mysql-server libapache2-mod-auth-mysql php5-mysql
sudo apt-get -y install php5 libapache2-mod-php5 php5-mcrypt
sudo apt-get -y install php5-gd
sudo apt-get -y install php5-intl

sudo apt-get -y update
sudo apt-get -y dist-upgrade
sudo apt-get -y install git
sudo apt-get install php5-curl

curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo cp -r /vagrant/.composer/ ~/.composer
sudo chmod -R 777 ~/.composer
composer global require fxp/composer-asset-plugin

sudo a2enmod rewrite
sudo cp /vagrant/conf/seo.conf /etc/apache2/sites-available
sudo a2ensite seo.conf
a2dissite 000-default.conf
service apache2 reload
sudo service apache2 restart
echo '127.0.0.1  seo.loc' | sudo tee --append /etc/hosts

# github token '1d99469bf28ec5f4018d69999cc6c41cf6846726'

cd /vagrant/
sudo composer update yiisoft/yii2 yiisoft/yii2-composer bower-asset/jquery.inputmask

#sudo locale-gen ru_RU
sudo locale-gen ru_RU.UTF-8
sudo update-locale

#mysql -uroot -p12345
#create database seoyii2 character set utf8 collate utf8_general_ci;
#php yii migrate