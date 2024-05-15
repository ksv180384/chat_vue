#!/bin/sh

echo "Node.js!"

cd /var/www/chat_socket_server

npm install
#npm run dev

#node socket_server.js
npm run start:watch