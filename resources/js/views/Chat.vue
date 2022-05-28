<template>
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 my-3">
            <span class="me-3">
                <router-link to="/"><FontAwesomeIcon icon="caret-left" /></router-link>
            </span>
            <span v-if="chat">{{ chat.title }}</span>
        </h1>

        <div>


            <div class="btn-group" role="group">
                <button type="button" class="btn btn-light" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 512" height="20px">
                        <path d="M64 360C94.93 360 120 385.1 120 416C120 446.9 94.93 472 64 472C33.07 472 8 446.9 8 416C8 385.1 33.07 360 64 360zM64 200C94.93 200 120 225.1 120 256C120 286.9 94.93 312 64 312C33.07 312 8 286.9 8 256C8 225.1 33.07 200 64 200zM64 152C33.07 152 8 126.9 8 96C8 65.07 33.07 40 64 40C94.93 40 120 65.07 120 96C120 126.9 94.93 152 64 152z"/>
                    </svg>
                </button>
                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <li><a @click.prevent="leaveChat" href="#" class="dropdown-item">Покинуть чат</a></li>
                    <li>
                        <a v-if="user.id === chat.creator_id" @click.prevent="deleteChat"
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
    <div class="card">
        <div class="row g-0">
            <div class="col-12 col-lg-3 col-xl-3 border-right">
                <SearchUserChat/>
                <ChatUsersList/>
            </div>
            <div class="col-12 col-lg-9 col-xl-9">
                <div class="position-relative">
                    <div class="chat-messages p-4" ref="messages_container">
                        <div v-if="messages">
                            <div ref="sentinel" class="text-center py-3">Загрузка...</div>
                            <div v-for="message in messages" :key="message.id">
                                <ChatMessage v-if="message.user.id === user.id" :message="message"/>
                                <ChatMessageLeft v-else :message="message"/>
                            </div>
                        </div>
                    </div>
                </div>
                <SendMessage :chat_id="chat.id"/>
            </div>
        </div>
    </div>
</template>

<script>

import ChatMessage from "../components/ChatMessage";
import ChatMessageLeft from "../components/ChatMessageLeft";
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
        SendMessage,
        SearchUserChat,
        ChatUsersList,
        ChatMessageLeft,
        ChatMessage,
        FontAwesomeIcon
    },
    data(){
        return {
            chat_id: this.$route.params.id,
            load_chat: false,
            page: 1,
        }
    },
    computed: {
        ...mapGetters([
            'chat',
            'user',
            'messages',
        ])
    },
    methods: {
        loadChat(){
            this.load_chat = true;
            api.get(`/chat/${this.chat_id}?page=${this.page}`)
                .then(res => {
                    this.$store.commit('setChat', res.chat);
                    this.$store.commit('setChatUsers', res.chat.users);
                    this.$store.commit('setMessages', res.messages.data.reverse());

                    this.load_chat = false;
                    this.page = this.page + 1;
                })
        },
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
        scrollToBottom(smooth) {
            const container = this.$refs.messages_container;

            if (smooth){
                container.scrollTo({
                    top: container.scrollHeight,
                    behavior: 'smooth'
                });
            }else{
                container.scrollTop = container.scrollHeight;
            }
        },
        setUpInterSectionObserver() {
            let options = {
                root: this.$refs.app,
                margin: "10px",
            };
            this.listEndObserver = new IntersectionObserver(
                this.handleIntersection,
                options
            );
            this.listEndObserver.observe(this.$refs.sentinel);
        },
        handleIntersection([entry]) {
            console.log("1");
            if (entry.isIntersecting && !this.loadChat) {
                console.log("подгружаем контент");
                //this.$store.dispatch('loadChat', this.$route.params.id);
                this.loadChat(this.$route.params.id);
            }
            /*
            if (entry.isIntersecting && this.canLoadMore && !this.isLoadingMore) {
                //this.loadMore();
                console.log('ok');
                this.loadNextPosts();
            }
            */
        },
    },
    mounted() {
        this.loadChat();
        //this.$store.dispatch('loadChat', this.$route.params.id);
        this.setUpInterSectionObserver();
    },
    updated() {
        this.scrollToBottom();
    }
}
</script>
