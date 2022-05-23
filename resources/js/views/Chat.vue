<template>
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 my-3">
            <span class="me-3">
                <router-link to="/"><FontAwesomeIcon icon="caret-left" /></router-link>
            </span>
            <span v-if="chat">{{ chat.title }}</span>
        </h1>

        <div>
            <button @click="leaveChat" class="btn btn-sm btn-primary">Покинуть чат</button>
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
                })
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
            //console.log("1");
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
