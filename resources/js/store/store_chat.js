const storeChat = {
    namespaced: true,
    state: () => {
        return {
            chat: null,
            users: [],
            messages: [],
            page: 1,
            next: false,
            load: false,
            // Тип добавления сообщения.
            // load - подгрузка сообщений при прокрутке вверх (скролл остается на месте)
            // send - отправка сообщения (скролл плавно прокручивается вниз)
            add_messages_type: 'load', //[load, send]
        }
    },
    actions: {

    },
    mutations: {
        setChat(state, chat){
            state.chat = chat;
        },
        setUsers(state, users){
            state.users = users;
        },
        pushUsers(state, users){
            if(Array.isArray(users)){
                state.users = [...users, ...state.users];
            }else{
                state.users = [users, ...state.users];
            }
        },
        setMessages(state, messages){
            state.messages = messages;
        },
        unshiftMessages(state, messages){
            if(Array.isArray(messages)){
                state.messages = [...messages, ...state.messages];
            }else{
                state.messages = [messages, ...state.messages];
            }

        },
        pushMessages(state, messages){
            if(Array.isArray(messages)) {
                state.messages = [...state.messages, ...messages];
            }else{
                state.messages = [...state.messages, messages];
            }
        },
        setPage(state, page){
            state.page = page;
        },
        setNext(state, next){
            state.next = next;
        },
        setLoad(state, load){
            state.load = load;
        },
        setAddMessagesType(state, val){
            state.add_messages_type = val;
        }
    },
    getters: {
        chat: state => state.chat,
        users: state => state.users,
        messages: state => state.messages,
        page: state => state.page,
        next: state => state.next,
        load: state => state.load,
        add_messages_type: state => state.add_messages_type,
    }
};

export default storeChat;
