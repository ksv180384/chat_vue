import { createStore } from 'vuex';

import socket from "../helpers/socket";

import storeUser from "./store_user";
import storeChatsList from "./store_chats_list";
import storeChat from "./store_chat";
import storeProfile from "./store_profile";

const store = createStore({
    state () {
        return {
            socket: socket,
            load_page: false,
        }
    },
    actions: {},
    mutations: {
        setLoadPage(state, load){
            state.load_page = load;
        }
    },
    getters: {
        load_page: state => state.load_page,
    },
    modules: {
        storeUser: storeUser,
        storeChatsList: storeChatsList,
        storeChat: storeChat,
        storeProfile: storeProfile,
    }
});

export default store;
