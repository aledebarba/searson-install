FROM nginx:stable-alpine

COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./nginx/certs /etc/nginx/ssl

RUN mkdir -p /var/www/html