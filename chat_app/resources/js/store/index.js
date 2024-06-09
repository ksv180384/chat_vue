import { createStore } from 'vuex';

import socket from '@/helpers/socket.js';

// import storeUser from '@/store/store_user.js';
import storeChatsList from '@/store/store_chats_list.js';
import storeChat from '@/store/__store_chat.js';
import storeMessageNotifications from '@/store/store_message_notifications.js';

const store = createStore({
    state () {
        return {
            socket: socket,
            users_online: [],
            load_page: false,
            is_site_not_work: false,
            is_socket_connect: true,
        }
    },
    actions: {},
    mutations: {
        setSocket(state, socket){
            state.socket = socket;
        },
        setLoadPage(state, load){
            state.load_page = load;
        },
        setUsersOnline(state, usersIds){
            state.users_online = usersIds;
        },
        addUserOnline(state, userId){
            state.users_online.push(userId);
        },
        removeUserOnline(state, userId){
            state.users_online = state.users_online.filter((item) => userId !== item);
        },
        setIsSiteNotWork(state, is_site_not_work){
            state.is_site_not_work = is_site_not_work;
        },
        setIsSocketConnect(state, is_socket_connect){
            state.is_socket_connect = is_socket_connect;
        },
    },
    getters: {
        socket: state => state.socket,
        load_page: state => state.load_page,
        users_online: state => state.users_online,
        is_site_not_work: state => state.is_site_not_work,
        is_socket_connect: state => state.is_socket_connect,
    },
    modules: {
        // storeUser: storeUser,
        storeChatsList: storeChatsList,
        storeChat: storeChat,
        storeMessageNotifications: storeMessageNotifications,
    }
});

export default store;
