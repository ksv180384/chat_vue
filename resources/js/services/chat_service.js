import api from "../helpers/api";
import store from "../store";
import {
    joinUserToChatSocket,
    laveUserToChatSocket,
    sendMessageSocket
} from "./socket_service";
import {userData} from "../helpers/helpers";

export const loadChatList = async () => {
    return await api.get(`/chat`);
}

export const loadChatListPage = async () => {
    const url = `/chat`;
    return await pageLoad(url);
}

export const loadChatPage = async (chatId) => {
    const url = `/chat/${chatId}`;
    return await pageLoad(url);
};

export const loadChatSettingsPage = async (chatId) => {
    const url = `/chat/${chatId}/settings`;
    return await pageLoad(url);
};

export const loadChatMessages = async (chatId, page) => {
    const url = `/chat/${chatId}/messages?page=${page}`;
    return await api.get(url);
};

export const laveChat = async (chatId) => {
    const res = await api.post(`/chat/lave`, { id: chatId });
    const userId = userData().id;
    laveUserToChatSocket(userId, chatId);
    return res;
}

export const joinUserToChat = async (data) => {
    const res = await api.post('/chat/join', data); // data = {chat_room_id: int, user_id: int}
    joinUserToChatSocket(data.user_id, res.chat);
    return res;
}

export const searchUserToChat = async (userName) => {
    return await api.get('/users/search/' + userName)
}

export const deleteChat = async (chatId) => {
    return await api.post(`/chat/delete`, { id: chatId });
}

export const addChat = async (chatData) => {
    return await api.post('/chat/create', chatData);
}

export const changeSettingsChat = async (chatId, chatData) => {
    return await api.post(`/chat/${chatId}/setting-change`, chatData);
}

export const sendMessage = async (messageData) => {
    const res = await api.post('/chat/messages/send', messageData);
    sendMessageSocket(messageData.chat_room_id, res);
    return res;
}

const pageLoad = async (url) => {
    store.commit('setLoadPage', true);
    const res = await api.get(url);
    store.commit('setLoadPage', false);
    return res;
}
