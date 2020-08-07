FROM        php:7.4.8-fpm
MAINTAINER  ACSEO <contact@acseo.fr>

#
# Here, we install system dependencies that will be needed to run configuration
# or PHP extensions installations.
#
RUN         apt-get -qq update --fix-missing && \
            apt-get -qq install -y build-essential git libicu-dev libjpeg62-turbo-dev \
            libmcrypt-dev libpng-dev libwebp-dev libxml2-dev wget gnupg \
            xvfb libxrender-dev zip zlib1g-dev unzip vim curl libc6-dev libfreetype6-dev libfontconfig libldap2-dev\
            psmisc xvfb libfontconfig wkhtmltopdf \
            libpcre3-dev sqlite3 libsqlite3-dev \
            psmisc xvfb libfontconfig wkhtmltopdf libonig-dev libzip-dev \
            gcc make autoconf libc-dev pkg-config libssl-dev librabbitmq-dev supervisor \
            zip unzip libzip-dev cron rsyslog

#
# Then, we change userid / groupid of www-data user (PHP-FPM) to ensure
# consistency between our different services and avoid permissions issues.
#
RUN         usermod -u 1000 www-data && \
            groupmod -g 1000 www-data

#
# We install & configure needed PHP extensions.
#

# NEVER USE IT IN DEV MODE (make processes very slowwwwww)
# RUN pecl install xdebug \
#     && docker-php-ext-enable xdebug

RUN         docker-php-ext-install \
              iconv \
              mysqli \
              opcache \
              pdo \
              pdo_mysql \
              xml \
              pdo_sqlite \
              zip

            # INTL
ENV         PHP_CPPFLAGS="$PHP_CPPFLAGS -std=c++11"
RUN         docker-php-ext-configure intl --enable-intl && \
            docker-php-ext-install intl

#
# Then, we copy & run Composer installation script.
#
ENV         COMPOSER_HOME=/var/composer
COPY        ./docker/services/app/composer-install /tmp/composer-install
RUN         chmod +x /tmp/composer-install && \
            /tmp/composer-install && \
            rm /tmp/composer-install
RUN         mkdir -p /var/composer && \
            chown -R www-data:www-data /var/composer
VOLUME      /var/composer

#
# Then, we configure directory where all sources will then be stored and set
# permissions accordingly to www-data user.
#
# USER        root
RUN         mkdir -p /app
COPY        . /app
RUN         test -d /app && chown -R www-data:www-data /app

ENV         SYMFONY_ENV=prod

RUN         pecl install xdebug \
            && docker-php-ext-enable xdebug

#
# Php custom conf
#
COPY        ./docker/services/app/php.ini /usr/local/etc/php/conf.d/php-sf.ini
COPY        ./docker/services/app/sf.conf /usr/local/etc/php-fpm.d/sf.conf
COPY        ./docker/services/app/rsyslog.conf /etc/rsyslog.conf

# Install nginx
RUN         apt-get update && apt-get install -y nginx
COPY        ./docker/services/app/conf.d /etc/nginx/conf.d
RUN         rm /etc/nginx/sites-enabled/default

ENV         SYMFONY_ENV=prod

RUN         touch /var/log/fpm-php.www.log && chmod 777 /var/log/fpm-php.www.log

#
# Entrypoint
#
COPY        ./docker/services/app/entrypoint /usr/local/bin/app-entrypoint
RUN         chmod u+x /usr/local/bin/app-entrypoint


#
# Final image configuration
#
VOLUME      /app
WORKDIR     /app

ENTRYPOINT  ["/usr/local/bin/app-entrypoint"]
CMD         ["php-fpm"]
