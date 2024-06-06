FROM nginx:alpine

COPY ssl/localhost.crt /etc/nginx/ssl/
COPY ssl/localhost.key /etc/nginx/ssl/

COPY . /var/www/html
