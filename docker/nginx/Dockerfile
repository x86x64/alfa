FROM nginx:1.13

ENV DEBIAN_FRONTEND noninteractive;

RUN ln -sf /usr/share/zoneinfo/Europe/London /etc/localtime \
    && rm -rf /etc/nginx/conf.d/default.conf \
    && rm -rf /var/log/nginx/access.log \
    && rm -rf /var/lib/apt/lists/*

COPY docker/nginx/rootfs /

WORKDIR /project

COPY docker/nginx/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
