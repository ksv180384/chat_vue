// const io = require('socket.io')(3077);

const express = require('express');
const app = express();
const http = require('http').createServer(app);
const io = require('socket.io')(http);

// Middleware для обработки POST-запросов
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

const socketMap = new Map();


// Добавляем новый чат
app.post('/create-chat', (req, res) => {

    // Получение данных из POST-запроса
    const data = req.body;
    const userId = +data.user_id;
    const chat = data.data.chat;
    const roomId = `chat_${chat.id}`;

    // получаем все сокет ид пользователя
    const userSockets = getSocketsByUserId(socketMap, userId);

    // Добавляем все соедиения пользователя в комнату и отсылаем каждому соединению событие добавления чата
    for (let [socketKey, userSocket] of userSockets) {
        io.sockets.sockets.get(socketKey).join(roomId);
        io.sockets.sockets.get(socketKey).emit('createChat', { chat: chat });
    }

    // Отправка ответа клиенту
    res.status(200).send('create-chat - запрос успешно обработан');
});

// Удаляем чат
app.post('/remove-chat', (req, res) => {

    // Получение данных из POST-запроса
    const data = req.body;
    const chatId = +data.data.chat_id;
    const chatRoom = `chat_${chatId}`;

    console.log(data);
    console.log(chatRoom);

    // Получаем все подключения к комнате
    const socketsRoom = io.sockets.adapter.rooms.get(chatRoom);
    console.log(socketsRoom);
    for (const socketItem of socketsRoom ) {
        // Каждое подключение открепляем от чата и отсылаем событие удаления чата
        const clientSocket = io.sockets.sockets.get(socketItem);
        clientSocket.emit('removeChat', { chat_id: chatId });
        //you can do whatever you need with this
        clientSocket.leave(chatRoom);
    }


    // Отправка ответа клиенту
    res.status(200).send('remove-chat - запрос успешно обработан');
});

/**
 * Оправка сообщения
 */
app.post('/send-message', (req, res) => {

    // Получение данных из POST-запроса
    const data = req.body;

    // Отправка данных через Socket.io
    io.to(`chat_${data.data.chat_room_id}`).emit('message', data.data);

    // Отправка ответа клиенту
    res.status(200).send('send-message - запрос успешно обработан');
});

/**
 * Вход пользователя в определенный чат
 */
app.post('/enter-room', (req, res) => {

    // Получение данных из POST-запроса
    const data = req.body;

    // TODO Использовать для отображения кто сейчас печатает

    // const userSocket = socketMap.get(parseInt(data.user_id));
    // Отправка данных через Socket.io
    // io.to(`chat_${data.data.chat_room_id}`).emit('message', data.data);

    // Отправка ответа клиенту
    res.status(200).send('enter-room - запрос успешно обработан');
});

/**
 * Приглашение пользователя в чат
 */
app.post('/user-join-to-chat', (req, res) => {

    // Получение данных из POST-запроса
    const data = req.body;

    const userId = +data.user_id;
    const chatData = data.chat_data;
    const joinRoomId = `chat_${chatData.id}`;

    // получаем все сокет ид поиглашаемого пользователя
    const userSockets = getSocketsByUserId(socketMap, userId);

    // Оповещаем всех участников комнаты о добавлении нового пользователя и добавляем пользователя в комнату
    io.sockets.to(joinRoomId).emit('addUserChat', { chat_data: chatData, user_id: userId });
    for (let [socketKey, userSocket] of userSockets) { // Добавляем пользователя в комнату
        io.sockets.sockets.get(socketKey).join(joinRoomId);
    }

    // Получаем пользователей из всех комнат к которым подключен приглашаемый пользователь
    const usersSocketIdByAllRooms = getUsersSocketIdByAllRooms(socket);

    // Получаем массив идентификаторов пользователей, которые состоят в комнате,
    // в которую сейчас пригласили пользователя
    const usersIds = getUsersIdsAllRooms(usersSocketIdByAllRooms, socketMap);
    // Отправляем данные пользователю, которого пригласили в чат, для отображения нового чата
    for (let [socketKey, userSocket] of userSockets) {
        io.sockets.sockets.get(socketKey).emit('joinUserChat', { chat_data: chatData, users_online: usersIds });
    }

    // Отправка ответа клиенту
    res.status(200).send('join-user-chat - запрос успешно обработан');
});


// Массив всех подключенных пользователей
// const allUsers = [];

io.on('connect', socket => {
    const userId = parseInt(socket.handshake.query.user_id);
    const chatsIds = socket.handshake.query.chats ? socket.handshake.query.chats.split(',') : [];

    socket.join(chatsIds.map(item => `chat_${item}`));

    socketMap.set(socket.id, { user_id: userId , rooms: socket.rooms });


    // Получаем пользователей из всех комнат к которым подключен текущий пользователь
    const usersSocketIdByAllRooms = getUsersSocketIdByAllRooms(socket);
    // рассылаем событие подключения нового пользователя
    usersSocketIdByAllRooms.forEach((item) => {
        socket.to(item).emit('userConnect', userId);
    });

    // Получаем массив идентификаторов пользователей с которыми текщий пользователь состоит в комнатах
    const usersIds = getUsersIdsAllRooms(usersSocketIdByAllRooms, socketMap);
    socket.emit('usersOnline', usersIds);

    // Сразу после подключения к сокет серверу, пользователь отправляет это событие
    /*
    socket.on('userConnect', (userId) => {

        allUsers[socket.id] = { user_id: +userId , rooms: socket.rooms };

        console.log('user id: ', userId);

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
    */

    // Присоединение пользователя к комнате
    socket.on('enterRoom', function(rooms) {

        // console.log(rooms);
        socket.join(rooms);
    });

    // // Пользователя подписали к чату
    // socket.on('joinUserChat', function (data) {
    //     const userId = +data.user_id;
    //     const chatData = data.chat_data;
    //     const joinRoomId = `chat_${chatData.id}`;
    //
    //     // получаем все сокет ид поиглашаемого пользователя
    //     // const joinUserSocketKey = getSocketIdByUserId(allUsers, userId);
    //     const userSockets = getSocketsByUserId(socketMap, userId);
    //
    //     // console.log(userSockets);
    //
    //
    //     // Оповещаем всех участников комнаты о добавлении нового пользователя и добавляем пользователя в комнату
    //     console.log(`Room id: ${joinRoomId}`)
    //     io.sockets.to(joinRoomId).emit('addUserChat', { chat_data: chatData, user_id: userId });
    //     for (let [socketKey, userSocket] of userSockets) { // Добавляем пользователя в комнату
    //         io.sockets.sockets.get(socketKey).join(joinRoomId);
    //     }
    //
    //     // console.log(socket)
    //     // const firstSocketConnectUser = Array.from(userSockets.values())[0];
    //
    //     // Получаем пользователей из всех комнат к которым подключен приглашаемый пользователь
    //     const usersSocketIdByAllRooms = getUsersSocketIdByAllRooms(socket);
    //
    //     console.log(`usersSocketIdByAllRooms: ${usersSocketIdByAllRooms}`)
    //     // const usersSocketIdByAllRooms = getUsersSocketIdByAllRooms(io.sockets.sockets.get(joinUserSocketKey));
    //
    //     // Получаем ид сокетов пользователей из всех комнат к которым подключен приглашаемый пользователь
    //     // const usersSocketIdByRoomId = getUsersSocketIdByAllByRoomId(socket, joinRoomId);
    //     // console.log(usersSocketIdByAllRooms);
    //     // Получаем массив идентификаторов пользователей, которые состоят в комнате,
    //     // в которую сейчас пригласили пользователя
    //     const usersIds = getUsersIdsAllRooms(usersSocketIdByAllRooms, socketMap);
    //     // io.sockets.sockets.get(joinUserSocketKey).emit('joinUserChat', { chat_data: chatData, users_online: usersIds });
    //     console.log('1111', socket.id);
    //     // Отправляем данные пользователю, которого пригласили в чат, для отображения нового чата
    //     for (let [socketKey, userSocket] of userSockets) {
    //         // io.sockets.sockets.get(socketKey).join(joinRoomId);
    //         io.sockets.sockets.get(socketKey).emit('joinUserChat', { chat_data: chatData, users_online: usersIds });
    //     }
    // });

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

    // socket.on('message', (data) => {
    //     socket.to(`chat_${data.chat_room_id}`).emit('message', data);
    // });

    // Событие потери соединения пользователем
    socket.on('disconnect', function() {
        const disconnectUserId = +socket.handshake.query.user_id;
        const userSocketId = socket.id;
        // const disconnectUserId = +socket.handshake.query.user_id;


        const userRooms = socketMap.get(userSocketId).rooms;
        socketMap.delete(userSocketId);

        // Если пользователь не подключится снова и у пользователя больше нет других подключенйи,
        // то генерируем событие отключения пользователя
        setTimeout(() => {

            // Считаем количество подключений пользователя
            const countSocketsUser = getSocketsByUserId(socketMap, disconnectUserId).size;

            // Если подключений пользователя нет, то генерируем событие отелючение пользователя
            if(!countSocketsUser){
                userRooms.forEach((item) => {
                    socket.to(item).emit('userDisconnect', disconnectUserId);
                });
            }
        }, 5000);
    });
});

// Запуск сервера
http.listen(3077, () => {
    console.log('Сервер запущен на порту 3077');
});

/**
 * Получаем пользователей из всех комнат к которым подключено текущее соединение пользователя
 * @param socket
 * @returns {Set<any>}
 */
function getUsersSocketIdByAllRooms(socket){
    let usersAllRooms = new Set();

    socket.rooms.forEach((item) => {
        console.log('Item: ', item);
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

    console.log(usersAllRooms);
    console.log(allUsers);

    usersAllRooms.forEach((item) => {
        usersIds.add(allUsers.get(item).user_id);

    });

    console.log([...usersIds]);

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

/**
 * Получаем сокеты определенного пользователя
 * @param socketMap
 * @param userId
 * @returns {Map<any, any>}
 */
function getSocketsByUserId(socketMap, userId){
    const result = new Map();

    for (let [key, userSocket] of socketMap) {
        if (userSocket.user_id === userId) {
            result.set(key, userSocket);
        }
    }
    return result;


    // return Object.keys(allUsers).filter((item) => {
    //     return allUsers[item].user_id === userId ;
    // });
}
