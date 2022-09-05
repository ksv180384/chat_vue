/*
const { Server } = require("socket.io");
const io = new Server(server);

io.on('connection', (socket) => {
    console.log('a user connected');
});
*/

const io = require('socket.io')(3077);
io.on("connect", socket => {
    console.log('user connect: ' + socket.id);
    console.log(socket.handshake.query);

    socket.on('enterRoom', function(room) {

        console.log('enter rom: ' + room);

        socket.join(room);
    });

    socket.on('leaveRoom', function(room) {

        socket.leave(room);
    });

    socket.on('message', (data) => {

        //console.log(data);

        socket.to(data.room).emit('message', data.message);
    });

    socket.on('userConnect', (userId) => {
        socket.rooms.forEach((item) => {
            socket.to(item).emit('userConnect', userId);
        });
    });

    socket.on('disconnect', function(rr) {
        console.log('user disconnect: ' + socket.handshake.query.user_id);

        console.log(socket.adapter.rooms);

        socket.rooms.forEach((item) => {
            console.log(item);
            socket.to(item).emit('userDisconnect', socket.handshake.query.user_id);
        });
    });

    /*
    socket.leave(room, () => {
        console.log('Пользователь вышел из чата.');

        socket.to(room).emit('notification', 'Пользователь вышел из чата.');
    });
     */
});
