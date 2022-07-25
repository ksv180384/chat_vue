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
    return await api.post('/chat/create', { title: chatData.title });
}

const pageLoad = async (url) => {
    store.commit('setLoadPage', true);
    const res = await api.get(url);
    store.commit('setLoadPage', false);
    return res;
}
