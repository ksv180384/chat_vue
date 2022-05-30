<template>
    <div class="chat-messages p-4" ref="messages_container">
        <div v-if="messages">
            <div v-show="messages_next" ref="sentinel" class="text-center py-3">Загрузка...</div>
            <div v-for="message in messages" :key="message.id">
                <ChatMessageItem v-if="chat.creator_id === user.id" :message="message"/>
                <ChatMessageItemLeft v-else :message="message"/>
            </div>
        </div>
    </div>
</template>

<script>

import ChatMessageItem from "../components/ChatMessageItem";
import ChatMessageItemLeft from "../components/ChatMessageItemLeft";
import {mapGetters} from "vuex";
//import store from "../store";
//import api from "../helpers/api";
//import router from "../router";

export default {
    name: "ChatMessagesList",
    components: {
        ChatMessageItemLeft,
        ChatMessageItem,
    },
    data(){
        return {
            next_page: false,
            scroll_type: 'auto',
            //messages: this.messagesList
        }
    },
    computed: {
        ...mapGetters([
            'chat',
            'messages',
            'messages_load',
            'messages_page',
            'messages_next',
            'user'
        ])
    },
    methods: {
        setUpInterSectionObserver() {
            let options = {
                root: this.$refs.messages_container,
                margin: "10px",
            };
            this.listEndObserver = new IntersectionObserver(
                this.handleIntersection,
                options
            );
            this.listEndObserver.observe(this.$refs.sentinel);
        },
        handleIntersection([entry]) {
            if (entry.isIntersecting) {
                console.log("подгружаем контент");
                //this.$store.dispatch('loadChat', this.$route.params.id);
                this.$store.dispatch('loadMessages', this.$route.params.id);
            }
            /*
            if (entry.isIntersecting && this.canLoadMore && !this.isLoadingMore) {
                //this.loadMore();
                console.log('ok');
                this.loadNextPosts();
            }
            */
        },
        scrollToBottom(type) {
            type = type || 'auto';
            const container = this.$refs.messages_container;

            container.scrollTo({
                top: container.scrollHeight,
                behavior: type
            });

            this.scroll_type = 'smooth';
        },
    },
    mounted() {
        //store.dispatch('loadChat', this.$route.params.id);
        //this.loadChat();
        //this.$store.dispatch('loadChat', this.$route.params.id);
        this.setUpInterSectionObserver();
    },
    updated() {
        //const pos = this.$refs.messages_container.scrollHeight;
        this.scrollToBottom(this.scroll_type);
    }
}
</script>

<style scoped>

</style>
