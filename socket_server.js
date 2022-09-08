
const allUsers = [];

const io = require('socket.io')(3077);
io.on("connect", socket => {
    console.log('user connect: ' + socket.id);
    //console.log(socket.adapter.rooms);
    //console.log(socket.handshake.query.user_id);

    socket.on('userConnect', (userId) => {

        allUsers[socket.id] = { user_id: userId , rooms: socket.rooms };

        const usersIds = [];
        Object.keys(allUsers).forEach(function(key, index) {
            usersIds.push(allUsers[key].user_id);
        });

        socket.rooms.forEach((item) => {
            socket.to(item).emit('userConnect', usersIds);
        });

        //console.log(allUsers);
    });

    socket.on('enterRoom', function(room) {

        //console.log('USER ENTER ROOM: ' + room);

        socket.join(room);
    });

    // Пользователя подписали к чату
    socket.on('joinUserChat', function (data) {
        const userId = +data.user_id;
        const chatData = data.chat_data;

        //console.log('user userId: ' + userId);
        //console.log(chatData);

        Object.keys(allUsers).forEach(function(key, index) {

            //console.log('user id: ' + allUsers[key].user_id);

            if(+allUsers[key].user_id === userId){

                //console.log('send user code: ' + key);

                socket.to(key).emit('joinUserChat', chatData);
                return true;
            }
        });
    });

    // Пользователь отписался от чата
    socket.on('laveUserChat', function (data) {
        const chatId = data.chat_id;
        socket.to(`chat_${chatId}`).emit('laveUserChat', data);
    });

    socket.on('leaveRoom', function(room) {

        //console.log('USER LAVE ROOM: ' + room);

        socket.leave(room);
    });

    socket.on('message', (data) => {
        socket.to(data.room).emit('message', data.message);
    });

    socket.on('disconnect', function() {
        console.log('user disconnect: ' + socket.handshake.query.user_id);

        const userId = allUsers[socket.id].user_id;
        const userRooms = allUsers[socket.id].rooms;

        delete allUsers[socket.id];

        userRooms.forEach((item) => {
            socket.to(item).emit('userDisconnect', userId);

        });

        //console.log(allUsers);

        //console.log(socket.adapter.rooms);

        /*
        socket.rooms.forEach((item) => {
            console.log(socket.handshake.query.user_id);

        });
         */
    });

    /*
    socket.leave(room, () => {
        console.log('Пользователь вышел из чата.');

        socket.to(room).emit('notification', 'Пользователь вышел из чата.');
    });
     */
});
