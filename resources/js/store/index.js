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
        }
    },
    actions: {
        loadChats({ commit }){
            api.get('/chat')
                .then(res => {
                    commit('setChats', res.chats)
                })
        },
        /*
        loadChat({ commit, state }, chat_id){
            commit('setLoadChat', true);
            api.get('/chat/' + chat_id + '?page=' + state.messages_paginate)
                .then(res => {
                    commit('setChat', res.chat);
                    commit('setChatUsers', res.chat.users);
                    commit('setMessages', res.messages.data.reverse());
                    commit('setMessagesPaginate', res.messages.current_page + 1);
                    commit('setLoadChat', false);
                    console.log(res.messages.current_page);
                })
        }
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
        setMessages(state, messages) {
            state.messages = messages;
        },
        pushMessages(state, messages) {
            state.messages = [...messages, ...state.messages];
        },
        setMessage(state, message) {
            state.messages = [...state.messages, message];
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
        chatUsers: state => {
            return state.chat_users;
        },
        messages: state => {
            return state.messages;
        },
    }
});

export default store;
