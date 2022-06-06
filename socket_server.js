/*
const { Server } = require("socket.io");
const io = new Server(server);

io.on('connection', (socket) => {
    console.log('a user connected');
});
*/

console.log('ok');
const io = require('socket.io')(3077);
io.on("connect", socket => {
    console.log('user connect');
});
