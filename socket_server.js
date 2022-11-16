const io = require('socket.io')(3077);

// Массив всех подключенных пользователей
const allUsers = [];

io.on("connect", socket => {
    console.log('user connect: ' + socket.id);
    //console.log(socket.adapter.rooms);
    //console.log(socket.handshake.query.user_id);

    //socket.emit('connect', 'CONNECT');

    // Сразу после подключения к сокет серверу, пользователь отправляет это событие
    socket.on('userConnect', (userId) => {

        allUsers[socket.id] = { user_id: +userId , rooms: socket.rooms };

        // Получаем пользователей из всех комнат к которым подключен текущий пользователь
        const usersSocketIdByAllRooms = getUsersSocketIdByAllRooms(socket);

        // рассылаем событие подключения нового пользователя
        usersSocketIdByAllRooms.forEach((item) => {
            socket.to(item).emit('userConnect', userId);
        });

        // Получаем массив идентификаторов пользователей с которыми текщий пользователь состоит в комнатах
        const usersIds = getUsersIdsAllRooms(usersSocketIdByAllRooms, allUsers);
        socket.emit('usersOnline', usersIds);

    });

    // Присоединение пользователя к комнате
    socket.on('enterRoom', function(room) {

        socket.join(room);
    });

    // Пользователя подписали к чату
    socket.on('joinUserChat', function (data) {
        const userId = +data.user_id;
        const chatData = data.chat_data;
        const joinRoom = `chat_${chatData.id}`;

        // получаем сокет ид поиглашаемого пользователя
        const joinUserSocketKey = getSocketIdByUserId(allUsers, userId);

        // Оповещаем всех участников комнаты о добавлении нового пользователя и добавляем пользователя в комнату
        io.sockets.to(joinRoom).emit('addUserChat', { chat_data: chatData, user_id: userId });
        io.sockets.sockets.get(joinUserSocketKey).join(joinRoom);

        // Получаем пользователей из всех комнат к которым подключен приглашаемый пользователь
        const usersSocketIdByAllRooms = getUsersSocketIdByAllRooms(io.sockets.sockets.get(joinUserSocketKey));
        // Получаем массив идентификаторов пользователей с которыми приглашаемый пользователь состоит в комнатах
        const usersIds = getUsersIdsAllRooms(usersSocketIdByAllRooms, allUsers);
        io.sockets.sockets.get(joinUserSocketKey).emit('joinUserChat', { chat_data: chatData, users_online: usersIds });
    });

    // Пользователь отписался от чата
    socket.on('laveUserChat', function (chatData) {
        const chatId = chatData.chat_id;
        socket.to(`chat_${chatId}`).emit('laveUserChat', chatData);
        socket.leave(`chat_${chatData.chat_id}`)
    });

    // Удаление чата
    socket.on('deleteChat', function (chat) {
        socket.to(`chat_${chat.id}`).emit('deleteChat', chat.id);
    });

    socket.on('leaveRoom', function(room) {

        socket.leave(room);
    });

    socket.on('message', (data) => {
        socket.to(data.room).emit('message', data.message);
    });

    // Событие потери соединения пользователем
    socket.on('disconnect', function() {
        const disconnectUserId = +socket.handshake.query.user_id;

        console.log(`user disconnect: ${disconnectUserId}`);

        const socketId = Object.keys(allUsers).find((key) => allUsers[key].user_id === disconnectUserId);

        if(!socketId){
            console.log(`Ошибка отключения пользователя ID: ${disconnectUserId}`);
            return true;
        }

        const userRooms = allUsers[socketId].rooms;

        delete allUsers[socketId];

        // Если пользователь не подключится снова, то генерируем событие отключения пользователя
        setTimeout(() => {

            // Считаем количество подключений пользователя
            const countSocketsUser = Object.keys(allUsers).filter(item => allUsers[item].user_id === disconnectUserId).length;

            // Если подключений пользователя нет, то генерируем событие отелючение пользователя
            if(!countSocketsUser){
                userRooms.forEach((item) => {
                    socket.to(item).emit('userDisconnect', disconnectUserId);
                });
            }
        }, 5000);
    });
});

/**
 * Получаем пользователей из всех комнат к которым подключено текущее соединение пользователя
 * @param socket
 * @returns {Set<any>}
 */
function getUsersSocketIdByAllRooms(socket){
    let usersAllRooms = new Set();

    socket.rooms.forEach((item) => {
        const roomUsers = io.sockets.adapter.rooms.get(item);
        for (let user of roomUsers) {
            usersAllRooms.add(user);
        }
    });

    return usersAllRooms;
}

/**
 * Получаем массив идентификаторов пользователей с которыми текщий пользователь состоит в комнатах
 * @param usersAllRooms
 * @param allUsers
 * @returns {Set<any>}
 */
function getUsersIdsAllRooms(usersAllRooms, allUsers){

    let usersIds = new Set();

    usersAllRooms.forEach((item) => {
        usersIds.add(allUsers[item].user_id);

    });

    return [...usersIds];
}

/**
 * Получаем сокет ид по ид пользователя
 * @param allUsers
 * @param userId
 * @returns {string}
 */
function getSocketIdByUserId(allUsers, userId){

    const socketId = Object.keys(allUsers).find((item) => {
        return allUsers[item].user_id === userId ;
    });

    return socketId;
}
