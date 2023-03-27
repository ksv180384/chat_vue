FROM node:19.7

WORKDIR /var/www/chat-vue.local

# Для смены типа переноса строк
RUN apt-get update && apt-get install -y dos2unix

COPY . /var/www/chat-vue.local

# Меняем тип переноса строк на LF
RUN dos2unix /var/www/chat-vue.local/_docker/nodejs/entrypoint.sh && chmod +x /var/www/chat-vue.local/_docker/nodejs/entrypoint.sh

ENTRYPOINT ["entrypoint"]
