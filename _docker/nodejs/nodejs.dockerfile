FROM node:20

WORKDIR /var/www/chat_socket_server

# Для смены типа переноса строк
RUN apt-get update && apt-get install -y dos2unix

COPY ./chat_socket_server /var/www/chat_socket_server
COPY ./_docker/nodejs /var/www/_docker/nodejs

# Меняем тип переноса строк на LF
RUN dos2unix /var/www/_docker/nodejs/entrypoint.sh && chmod +x /var/www/_docker/nodejs/entrypoint.sh

ENTRYPOINT ["entrypoint"]
