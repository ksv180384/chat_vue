import { createStore } from 'vuex';
import storeMessages from './store_messages';
import storeProfile from "./store_profile";

import api from '../helpers/api';
import socket from "../helpers/socket";
import { userData } from '../helpers/helpers';

// Create a new store instance.
const store = createStore({
    state () {
        return {
            user: userData(),
            count: 0,
            chat: {},
            chat_list: [],
            chat_users: [],
            socket: socket,
            messages_page: 1,
        }
    },
    actions: {
        loadChats({ commit }){
            api.get('/chat')
                .then(res => {
                    commit('setChats', res.chats)
                });
        },
        /*
        loadChat({ commit }, chat_id){
            this.messages_load = true;
            api.get(`/chat/${chat_id}?page=${this.state.messages_page}`)
                .then(res => {
                    commit('setChat', res.chat);
                    commit('setChatUsers', res.chat.users);
                    commit('setMessages', res.messages.data.reverse());

                    this.state.messages_load = false;

                    if(res.messages.next_page_url){
                        const page = this.state.messages_page + 1;
                        commit('setMessagesPage', page);
                        this.state.messages_next = true;
                    }else{
                        this.state.messages_next = false;
                    }
                })
        },
        */
    },
    mutations: {
        setUser(state, user) {
            state.user = user
        },
        setChats(state, chats) {
            state.chat_list = chats
        },
        addChat(state, chat){
            state.chat_list = [...state.chat_list, chat]
        },
        setChat(state, chat) {
            state.chat = chat
        },
        setChatUsers(state, users) {
            state.chat_users = users
        },
        setMessagesPage(state, page) {
            state.messages_page = page
        },
    },
    getters: {
        user: state => {
            return state.user;
        },
        chats: state => {
            return state.chat_list;
        },
        chat: state => {
            return state.chat;
        },
        chat_users: state => {
            return state.chat_users;
        },
    },
    modules: {
        storeMessages: storeMessages,
        storeProfile: storeProfile,
    }
});

export default store;
