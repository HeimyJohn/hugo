#! /usr/bin/env bash
sudo apt update && sudo apt upgrade -y
# sudo apt install -y lsb-release apt-transport-https ca-certificates
# sudo sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/sury-php.list'
# wget -qO - https://packages.sury.org/php/apt.gpg | sudo apt-key add -
sudo apt install php8.3-fpm -y
sudo systemctl enable php8.3-fpm
sudo systemctl start php8.3-fpm
sudo a2dismod php8.3
sudo a2enmod proxy_fcgi setenvif
printf "<IfModule proxy_fcgi_module> \n    <FilesMatch \.php$>\n        SetHandler \"proxy:unix:/run/php/php8.3-fpm.sock|fcgi://localhost/\"\n    </FilesMatch>\n</IfModule>" | sudo tee /etc/apache2/conf-available/php8.3-fpm.conf
sudo a2enconf php8.3-fpm
sudo systemctl restart apache2
sudo systemctl reload apache2