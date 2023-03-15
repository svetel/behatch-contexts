FROM php:8.1-fpm

# Install general libs
RUN apt update -y && apt install -y git \
            zip \
            unzip

RUN mkdir -p ~/.ssh && \
    ssh-keyscan -H gitlab.internalone.com >> ~/.ssh/known_hosts

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --version=2.3.8 && mv composer.phar /usr/local/bin/composer

WORKDIR /code

CMD ["php-fpm"]
