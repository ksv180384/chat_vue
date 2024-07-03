import api from '@/services/api';
import { pageLoad } from '@/services/service.js';

export const loadChatList = async () => {
    return await api.get(`/chat`);
}

export const loadUserChats = async () => {
    const url = `/chat/user-chats`;
    return await api.get(url);
}

export const loadChatPage = async (chatId) => {
    const url = `/chat/${chatId}`;
    return await pageLoad(url);
};

// export const loadChatSettingsPage = async (chatId) => {
//     const url = `/chat/${chatId}/settings`;
//     return await pageLoad(url);
// };

export const loadChatMessages = async (chatId, page) => {
    const url = `/chat/${chatId}/messages?page=${page}`;
    return await api.get(url);
};

export const laveChat = async (chatId) => {
    const res = await api.post(`/chat/lave`, { id: chatId });
    // const userId = userData().id;
    // laveUserToChatSocket(userId, chatId);
    return res;
}

export const joinUserToChat = async (data) => {
    const res = await api.post('/chat/join', data); // data = {chat_room_id: int, user_id: int}
    //joinUserToChatSocket(data.user_id, res.chat);
    return res;
}

export const searchUserToChat = async (userName) => {
    return await api.get('/users/search/' + userName)
}

export const deleteChat = async (chatId) => {
    const res = await api.post(`/chat/delete`, { id: chatId });
    // deleteChatToChatSocket(chatId);
    return res;
}

export const addChat = async (chatData) => {
    const res = await api.post('/chat/create', chatData);
    // createChatToChatSocket(res.id);
    return res
}

export const changeSettingsChat = async (chatId, chatData) => {
    return await api.post(`/chat/${chatId}/setting-change`, chatData);
}

export const sendMessage = async (messageData) => {
    const res = await api.post('/chat/messages/send', messageData);
    // sendMessageSocket(messageData.chat_room_id, res);
    return res;
}
