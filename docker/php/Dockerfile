FROM php:8.3-fpm

RUN apt-get update && apt-get install -y git zip wget curl unzip vim supervisor systemd sudo
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sSLf \
        -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions xdebug pgsql pdo pdo_pgsql intl zip apcu mbstring sodium

ENV USER=docker
ENV HOME=/home/$USER
ENV WORKDIR=/var/www/html
RUN useradd -ms /bin/bash $USER && adduser $USER sudo
RUN echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers
RUN mkdir -p $HOME && chown $USER:$USER $HOME
WORKDIR $WORKDIR

COPY ./messenger-workers.conf /etc/supervisor/conf.d/messenger-workers.conf
COPY ./run_php.sh $HOME/run_php.sh
RUN chown docker:docker $HOME/run_php.sh && chmod 777 $HOME/run_php.sh

USER $USER
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    php composer-setup.php \
    php -r "unlink('composer-setup.php');" \
    sudo mv composer.phar /usr/local/bin/composer

CMD ["/home/docker/run_php.sh"]