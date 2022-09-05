import { createStore } from 'vuex';

import socket from "../helpers/socket";

import storeUser from "./store_user";
import storeChatsList from "./store_chats_list";
import storeChat from "./store_chat";
import storeProfile from "./store_profile";
import storeMessageNotifications from "./store_message_notifications";

const store = createStore({
    state () {
        return {
            socket: socket,
            users_online: [],
            load_page: false,
        }
    },
    actions: {},
    mutations: {
        setLoadPage(state, load){
            state.load_page = load;
        },
        addUserOnline(state, userId){
            state.users_online.push(userId);
        },
        removeUserOnline(state, userId){
            state.users_online = state.users_online.filter((item) => userId !== item);
        },
    },
    getters: {
        load_page: state => state.load_page,
        users_online: state => state.users_online,
    },
    modules: {
        storeUser: storeUser,
        storeChatsList: storeChatsList,
        storeChat: storeChat,
        storeProfile: storeProfile,
        storeMessageNotifications: storeMessageNotifications,
    }
});

export default store;
