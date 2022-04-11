import { createStore } from 'vuex';
import api from '../helpers/api';
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
            messages: [],
            messages_paginate: 1,
        }
    },
    actions: {
        loadChats({ commit }){
            api.get('/chat')
                .then(res => {
                    commit('setChats', res.chats)
                })
        },
        loadChat({ commit }, chat_id){
            api.get('/chat/' + chat_id)
                .then(res => {
                    commit('setChat', res.chat);
                    commit('setChatUsers', res.chat.users);
                    commit('setMessages', res.messages.data);
                    commit('setMessagesPaginate', res.messages.curent_page);
                })
        }
    },
    mutations: {
        setUser (state, user) {
            state.user = user
        },
        setChats (state, chats) {
            state.chat_list = chats
        },
        addChat(state, chat){
            state.chat_list = [...state.chat_list, chat]
        },
        setChat (state, chat) {
            state.chat = chat
        },
        setChatUsers (state, users) {
            state.chat_users = users
        },
        setMessages (state, messages) {
            state.messages = messages
        },
        setMessagesPaginate (state, messages_paginate) {
            state.messages_paginate = messages_paginate
        }
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
        chatUsers: state => {
            return state.chat_users;
        },
        messages: state => {
            return state.messages;
        },
        messagesPaginate: state => {
            return state.messages_paginate;
        },
    }
});

export default store;
