import api from "../helpers/api";
import store from "../store";

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
    return await api.post(`/chat/lave`, { id: chatId });
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
    store.commit('storeChat/setLoadSend', true);
    const res = await api.post('/chat/messages/send', messageData);
    sendMessageSocket(messageData.chat_room_id, res);
    store.commit('storeChat/setLoadSend', false);
    return res;
}

const pageLoad = async (url) => {
    store.commit('setLoadPage', true);
    const res = await api.get(url);
    store.commit('setLoadPage', false);
    return res;
}

const sendMessageSocket = (chatId, messageData) => {
    store.state.socket.emit(
        'message',
        {room: `chat_${chatId}`, message: messageData}
    );
}
