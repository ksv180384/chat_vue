import {io} from "socket.io-client";
import store from "../store";
import {userData} from "./helpers";
import router from "../router";
import {laveUserToChatSocket} from "../services/socket_service";

const connectionOptions =  {
    "force new connection" : true,
    "reconnectionAttempts": "Infinity", //avoid having user reconnect manually in order to prevent dead clients after a server restart
    "timeout" : 10000, //before connect_error and connect_timeout are emitted.
    "transports" : ["websocket"],
    autoConnect: false,
};
const socket = io("http://localhost:3077", connectionOptions);

// Событие подключения к серверу
socket.on('connect', async (data) => {

    const chatsList = store.state.storeChatsList.chats;
    if(chatsList.length === 0){
        console.log('no chats');
    }

    // Добавляем доступные комнаты
    chatsList.forEach((el, i) => {
        socket.emit('enterRoom', `chat_${el.id}`);
    });

    const userId = userData().id;
    socket.emit('userConnect', userId);
    store.commit('setIsSocketConnect', true);
});

// Событие получения сообщения, получают все, кто состоит хоть в одном из чатов с отправителем
socket.on('message', function(data){

    const chatId = +data.chat_room_id;
    const selectChat = store.state.storeChat.chat ? +store.state.storeChat.chat.id : null;

    if(chatId === selectChat){
        store.commit('storeChat/pushMessages', data);
    }
});

// Когда пользователь присоединяется к чату, все пользователи (с которыми но состоит в чате) получают это саобытие
socket.on('joinUserChat', async (data) => {
    store.commit('storeChatsList/pushChats', data.chat_data);
    store.commit('setUsersOnline', data.users_online);
});

socket.on('addUserChat', async (data) => {
    const selectChat = store.state.storeChat.chat ? +store.state.storeChat.chat.id : null;
    if(selectChat === +data.chat_data.id){
        store.commit('storeChat/setUsers', data.chat_data.users);
    }
    store.commit('addUserOnline', data.user_id);
});

// Когда пользователь отписывается от чата, все пользователи (с которыми но состоит в чате) получают это событие
socket.on('laveUserChat', async (data) => {
    if(+store.state.storeChat.chat?.id === +data.chat_id){
        store.commit('storeChat/removeUser', data.user_id);
    }
    store.commit('removeUserOnline', data.user_id);
});

// Когда удаляют чат, пользователи состоящие в чате получаю это событие
socket.on('deleteChat', async (chatId) => {
    store.commit('storeChatsList/removeChat', +chatId);

    if(router.currentRoute.value.path === `/chat/${chatId}`){
        router.push('/');
    }

    //laveUserToChatSocket(store.getters["storeUser/id"], chatId);
});

// При подключении к чату, обратно получаем это событие со всеми пользователями онлайн
socket.on('usersOnline', async (usersIds) => {
    store.commit('setUsersOnline', usersIds);
});

// Кода пользователь подключился к чату, все пользователи (с которыми но состоит в чате) получают это событие
socket.on('userConnect', async (userId) => {
    store.commit('addUserOnline', userId);
});

// Когда пользователю отключается от чата, все пользователи (с которыми но состоит в чате) получают это событие
socket.on('userDisconnect', async (userId) => {
    store.commit('removeUserOnline', userId);
});

// Событие срабатывает при потере соединения с сервером
socket.on('disconnect', async () => {
    store.commit('setIsSocketConnect', false);
    store.commit('setUsersOnline', []);
});

export default socket;
