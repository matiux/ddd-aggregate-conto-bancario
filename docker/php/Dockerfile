FROM matiux/php:7.3.2-fpm-alpine3.9-dev

USER root

RUN apk add --no-cache \
    openssh

USER utente

RUN echo 'alias memflush="echo \"flush_all\" | nc servicememcached 11211 -q 1"' >> /home/utente/.zshrc \
    && echo 'alias test="./vendor/bin/simple-phpunit"' >> /home/utente/.zshrc \
    && echo 'alias sfcc="rm -Rf var/cache/*"' >> /home/utente/.zshrc \
    && echo 'alias xon="export XDEBUG_CONFIG=\"idekey=phpstorm-xdebug\"; export XDEBUG_CONFIG=\"remote_enable=1\""' >> /home/utente/.zshrc \
    && echo 'alias xoff="export XDEBUG_CONFIG=\"\"; export XDEBUG_CONFIG=\"remote_enable=0\""' >> /home/utente/.zshrc

#COPY --chown=utente:utente ssh/* /home/utente/.ssh/
#RUN  chmod 600 /home/utente/.ssh/id_rsa
