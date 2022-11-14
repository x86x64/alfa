#!/usr/bin/env bash

if [ ! -f /etc/nginx/conf.d/app.conf ]; then
    envsubst '$APP_HOST' < /etc/nginx/conf.d/app.conf.template > /etc/nginx/conf.d/app.conf
fi
exec nginx -g "daemon off;"
