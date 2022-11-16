import store from "../store";

export const sendMessageSocket = (chatId, messageData) => {
    store.state.socket.emit(
        'message',
        { room: `chat_${chatId}`, message: messageData }
    );
}

export const createChatToChatSocket = (chatId) => {
    store.state.socket.emit(
        'enterRoom',
        `chat_${chatId}`
    );
}

export const joinUserToChatSocket = (userId, chatData) => {
    store.state.socket.emit(
        'joinUserChat',
        { user_id: userId, chat_data: chatData }
    );
}

export const laveUserToChatSocket = (userId, chatId) => {
    store.state.socket.emit(
        'laveUserChat',
        { user_id: userId, chat_id: chatId }
    );
}

export const deleteChatToChatSocket = (chatId) => {
    store.state.socket.emit(
        'deleteChat',
        { id: chatId }
    );
}

