FROM registry.redhat.io/rhscl/php-56-rhel7 AS base

USER 0

ADD . .
RUN chown -R 1001:0 .

USER 1001

RUN TEMPFILE=$(mktemp) \
    && curl -o "$TEMPFILE" "https://getcomposer.org/installer" \
    && php <"$TEMPFILE" \
    && ./composer.phar install --no-interaction --no-ansi --optimize-autoloader
RUN mkdir -p ${APP_DATA}/storage/{app,framework,logs} | true
RUN mkdir -p ${APP_DATA}/storage/framework/{cache,dbcache,sessions} | true
RUN chmod -R 775 ${APP_DATA}/storage

CMD /usr/libexec/s2i/run
