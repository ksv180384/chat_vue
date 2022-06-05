import api from "../helpers/api";

const storeMessages = {
    namespace: true,
    state: {
        messages: [],
        messages_load: false,
        messages_page: 1,
        messages_next: false,
    },
    actions: {
        loadMessages({ commit }, chat_id){
            this.messages_load = true;
            api.get(`/chat/${chat_id}/messages?page=${this.state.messages_page}`)
                .then(res => {
                    commit('pushMessages', res.data.reverse());

                    this.state.messages_load = false;

                    if(res.next_page_url){
                        this.state.messages_page = this.state.messages_page + 1;
                        this.state.messages_next = true;
                    }else{
                        this.state.messages_next = false;
                    }
                })
        },
    },
    mutations: {
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
        messages: state => {
            return state.messages;
        },
        messages_load: state => {
            return state.messages_load;
        },
        messages_page: state => {
            return state.messages_page;
        },
        messages_next: state => {
            return state.messages_next;
        },
    }
}

export default storeMessages;
