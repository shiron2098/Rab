<?php
session_start();
require_once('CreateTask.php');
require_once('RabbitMqSendMessageDAWS.php');


class Streams extends CreateTask
{


    /*for ($i = 0; $i < 2; $i += 1) {
        exec("php MyDataProvider.php $i > test.txt &");
    }*/

    /*for ($i=0; $i<10; $i++) {
        // open ten processes
        for ($j=0; $j<10; $j++) {
            $pipe[$j] = popen(__dir__ . '/MyDataProvider.php', 'w');
        }

        // wait for them to finish
        for ($j=0; $j<10; ++$j) {
            pclose($pipe[$j]);
        }
    }*/
}



rmdir('/home/shiro/Documents/Rab/File/6');





/*CONFIGURE_STRING="--prefix=/etc/php/7.0\
--with-bz2 \
--with-zlib \
--enable-zip \
--disable-cgi \
--enable-soap \
--enable-intl \
--with-openssl \
--with-readline \
--with-curl \
--enable-ftp \
--enable-mysqlnd \
--with-mysqli=mysqlnd \
--with-pdo-mysql=mysqlnd \
--enable-sockets \
--enable-pcntl \
--with-pspell \
--with-enchant \
--with-gettext \
--with-gd \
--enable-exif \
--with-jpeg-dir \
--with-png-dir \
--with-freetype-dir \
--with-xsl \
--enable-bcmath \
--enable-mbstring \
--enable-calendar \
--enable-simplexml \
--enable-json \
--enable-hash \
--enable-session \
--enable-xml \
--enable-wddx \
--enable-opcache \
--with-pcre-regex \
--with-config-file-path=/etc/php7/cli \
--with-config-file-scan-dir=/etc/php7/etc \
--enable-cli \
--enable-maintainer-zts \
--with-tsrm-pthreads \
--enable-debug \
--enable-fpm \
--with-fpm-user=www-data \
--with-fpm-group=www-data"

    ./configure $CONFIGURE_STRING

    sudo ln -s /etc/php/7.0--with-bz2/bin/php /usr/bin/php*/