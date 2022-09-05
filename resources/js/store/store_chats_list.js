const storeChatsList = {
    namespaced: true,
    state: () => {
        return {
            chats: [],
            load_chats: false,
        }
    },
    mutations: {
        setChats(state, chats){
            state.chats = chats;
        },
        pushChats(state, chats){
            if(Array.isArray(chats)){
                state.chats = [...state.chats, ...chats];
            }else{
                state.chats = [...state.chats, chats]
            }
        },
        changeChat(state, chat){
            state.chats = state.chats.map((chatItem) => {
                chatItem = chatItem.id === chat.id ? chat : chatItem;
                return chatItem;
            });

            console.log(state.chats);
        },
        setLoad(state, load){
            state.load_chats = load;
        }
    },
    getters: {
        chats: state => state.chats,
        load_chats: state => state.load_chats,
    },
};

export default storeChatsList;
