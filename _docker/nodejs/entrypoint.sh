#!/bin/sh

echo "Node.js!"

cd /var/www/chat-vue.local

npm install
npm run dev

node socket_server.js
