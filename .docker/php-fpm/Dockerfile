FROM php:8.1-fpm-alpine3.16

# Install general libs
RUN apk add git \
            zip \
            unzip \
            openssh-client \
            gcc \
            g++ \
            make \
            autoconf \
            grep \
            libffi-dev \
            icu-dev \
            libbz2 \
            bzip2-dev \
            libxml2 \
            libxml2-dev \
            libxslt \
            libxslt-dev \
            gettext-dev

RUN mkdir -p ~/.ssh && \
    ssh-keyscan -H gitlab.internalone.com >> ~/.ssh/known_hosts

# Install PHP extensions
RUN docker-php-ext-install pdo \
                            pdo_mysql \
                            bz2 \
                            calendar \
                            soap \
                            sockets \
                            exif \
                            gettext \
                            mysqli \
                            shmop \
                            sysvmsg \
                            sysvsem \
                            xsl \
                            bcmath \
                            intl




# Please note that the Blackfire Probe is dependent on the session module.
# If it isn't present in your install, you will need to enable it yourself.
# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --version=2.3.8 && mv composer.phar /usr/local/bin/composer

WORKDIR /code

CMD ["php-fpm"]
