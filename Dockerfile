FROM ubuntu:noble

RUN apt-get -y update && apt-get install -y php8.3 wget git php8.3-xml php8.3-zip php8.3-pdo php8.3-mysql unzip
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
RUN mkdir /app

RUN chown -R www-data:www-data /app

COPY ./.infrastructure/apache2/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default.conf
RUN a2enmod rewrite
RUN service apache2 restart
CMD ["/usr/sbin/apachectl", "-D", "FOREGROUND"]