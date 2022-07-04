/*
const { Server } = require("socket.io");
const io = new Server(server);

io.on('connection', (socket) => {
    console.log('a user connected');
});
*/

const io = require('socket.io')(3077);
io.on("connect", socket => {
    console.log('user connect');

    socket.on('enterRoom', function(room) {

        socket.join(room);
    });

    socket.on('leaveRoom', function(room) {

        socket.leave(room);
    });

    socket.on('message', (data) => {

        console.log(data);

        socket.to(data.room).emit('message', data.message);
    });

    /*
    socket.leave(room, () => {
        console.log('Пользователь вышел из чата.');

        socket.to(room).emit('notification', 'Пользователь вышел из чата.');
    });
     */
});
