import api from "../helpers/api";
import store from "../store";

export const loadChatPage = async (chatId) => {
    const url = `/chat/${chatId}`;
    return await pageLoad(url);
};

export const loadChatListPage = async () => {
    const url = `/chat`;
    return await pageLoad(url);
}

export const laveChat = async (chatId) => {
    return await api.post(`/chat/lave`, { id: chatId });
}

export const deleteChat = async (chatId) => {
    return await api.post(`/chat/delete`, { id: chatId });
}

export const addChat = async (chatData) => {
    return await api.post('/chat/create', chatData);
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
