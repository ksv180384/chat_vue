<template>
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 my-3">
            <span class="me-3">
                <router-link to="/"><FontAwesomeIcon icon="caret-left" /></router-link>
            </span>
            <span v-if="chat">{{ chat.title }}</span>
        </h1>

        <div v-if="chat">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-light" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 512" height="20px">
                        <path d="M64 360C94.93 360 120 385.1 120 416C120 446.9 94.93 472 64 472C33.07 472 8 446.9 8 416C8 385.1 33.07 360 64 360zM64 200C94.93 200 120 225.1 120 256C120 286.9 94.93 312 64 312C33.07 312 8 286.9 8 256C8 225.1 33.07 200 64 200zM64 152C33.07 152 8 126.9 8 96C8 65.07 33.07 40 64 40C94.93 40 120 65.07 120 96C120 126.9 94.93 152 64 152z"/>
                    </svg>
                </button>
                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <li><a @click.prevent="leaveChat" href="#" class="dropdown-item">Покинуть чат</a></li>
                    <li>
                        <a v-if="user.id === chat.creator.id" @click.prevent="deleteChat"
                           href="#"
                           class="dropdown-item"
                        >
                            Удалить чат
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div v-if="chat" class="card">
        <div class="row g-0">
            <div class="col-12 col-lg-3 col-xl-3 border-right">
                <SearchUserChat/>
                <ChatUsersList/>
            </div>
            <div class="col-12 col-lg-9 col-xl-9">
                <div class="position-relative">
                    <ChatMessagesList :prop_messages_list="messages"
                                      :prop_next_page="next_page"
                    />
                </div>
                <SendMessage :chat_id="chat.id" @onSendMessage="pushMessage"/>
            </div>
        </div>
    </div>
</template>

<script>

import ChatMessagesList from "../components/ChatMessagesList";
import ChatUsersList from "../components/ChatUsersList";
import SearchUserChat from "../components/SearchUserChat";
import SendMessage from "../components/SendMessage";

import { mapGetters } from 'vuex'

import { library } from '@fortawesome/fontawesome-svg-core';
import { faCaretLeft } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import api from "../helpers/api";
import router from "../router";

library.add(faCaretLeft)

export default {
    name: "chat",
    components: {
        ChatMessagesList,
        SendMessage,
        SearchUserChat,
        ChatUsersList,
        FontAwesomeIcon
    },
    data(){
        return {
            chat: null,
            chat_users: [],
            load_chat: false,
            messages: [],
            chat_id: this.$route.params.id,
            next_page: false,
        }
    },
    computed: {
        ...mapGetters([
            'user',
        ])
    },
    methods: {
        leaveChat(){
            api.post(`/chat/lave`, { id: this.chat_id })
                .then(res => {
                    router.push('/');
                });
        },
        deleteChat(){
            api.post(`/chat/delete`, { id: this.chat_id })
                .then(res => {
                    router.push('/');
                });
        },
        pushMessage(messageData){
            //this.$store.commit('setMessage', messageData);

            console.log(messageData);
        },
        loadChat(){
            this.load_chat = true;
            api.get(`/chat/${this.chat_id}?page=${this.page}`)
                .then(res => {

                    this.load_chat = false;

                    this.chat = res.chat;
                    this.chat_users = res.chat.users;
                    this.messages = res.messages.data.reverse();

                    if(res.messages.next_page_url){
                        this.next_page = true;
                    }
                })
        },
    },
    mounted() {
        this.loadChat();
        this.$store.state.socket.on('message', function(data){
            this.pushMessage(data);
        }.bind(this));

        this.$store.state.socket.emit('enterRoom', `chat_${this.chat_id}`);

    },
    unmounted() {
        this.$store.state.socket.emit('leaveRoom', `chat_${this.chat_id}`);
    }
}
</script>
