import {io} from "socket.io-client";
import store from "../store";
import {userData} from "./helpers";

const connectionOptions =  {
    "force new connection" : true,
    "reconnectionAttempts": "Infinity", //avoid having user reconnect manually in order to prevent dead clients after a server restart
    "timeout" : 10000, //before connect_error and connect_timeout are emitted.
    "transports" : ["websocket"],
    autoConnect: false,
};
const socket = io("http://localhost:3077", connectionOptions);


socket.on('connect', async () => {

    const chatsList = store.state.storeChatsList.chats;
    if(chatsList.length === 0){
        console.log('no chats');
    }

    // Добавляем доступные комноты
    chatsList.forEach((el, i) => {
        socket.emit('enterRoom', `chat_${el.id}`);
    });

    const userId = userData().id;
    socket.emit('userConnect', userId);
});

socket.on('message', function(data){

    const chatId = +data.chat_room_id;
    const selectChat = store.state.storeChat.chat ? +store.state.storeChat.chat.id : null;

    console.log('message chat id: ' + chatId);

    if(chatId === selectChat){
        store.commit('storeChat/pushMessages', data);
    }
});

socket.on('joinUserChat', async (chatData) => {
    store.commit('storeChatsList/pushChats', chatData);
});

socket.on('laveUserChat', async (data) => {
    //console.log('lave chat id: ' + store.state.storeChat.chat.id);
    if(+store.state.storeChat.chat.id === +data.chat_id){
        store.commit('storeChat/removeUser', data.user_id);
    }
});

socket.on('userConnect', async (usersIds) => {

    console.log('user connect:');
    console.log(usersIds);

    //store.commit('addUserOnline', userId);
});

socket.on('userDisconnect', async (userId) => {

    console.log('user disconnect: ' + userId);

    //store.commit('removeUserOnline', userId);
});

export default socket;
