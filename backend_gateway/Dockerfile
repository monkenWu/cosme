FROM ubuntu:18.04

RUN apt-get update -y
RUN apt-get install software-properties-common -y
RUN add-apt-repository ppa:ondrej/php -y
RUN apt-get update -y
RUN DEBIAN_FRONTEND="noninteractive" apt-get install -y php7.4
RUN apt-get install -y php-pear php7.4-curl php7.4-dev php7.4-gd php7.4-mbstring php7.4-zip php7.4-mysql php7.4-xml php7.4-fpm php7.4-intl
RUN curl -s https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
COPY service /app
WORKDIR /app
RUN composer install --no-dev
RUN php spark ciroad:init
COPY .env /app/.env
COPY .rr.yaml /app/.rr.yaml
RUN chown -R www-data:www-data /app/writable
RUN chmod 776 -R /app/writable

EXPOSE 80

COPY run.sh /run.sh
RUN chmod a+rx /run.sh

CMD ["/bin/bash","-c","/run.sh"]