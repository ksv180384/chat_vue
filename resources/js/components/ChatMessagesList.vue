<template>
    <div class="chat-messages p-4" ref="messages_container">
        <div v-if="messages">
            <div v-show="next_page" ref="sentinel" class="text-center py-3">Загрузка...</div>
            <div ref="messages_list_container">
                <div v-for="message in messages" :key="message.id">
                    <ChatMessageItem v-if="message.user.id === user.id" :message="message"/>
                    <ChatMessageItemLeft v-else :message="message"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import ChatMessageItem from "../components/ChatMessageItem";
import ChatMessageItemLeft from "../components/ChatMessageItemLeft";
import {mapGetters, mapState} from "vuex";
import api from "../helpers/api";

export default {
    name: "ChatMessagesList",
    components: {
        ChatMessageItemLeft,
        ChatMessageItem,
    },
    props: ['prop_messages_list', 'prop_next_page'],
    data(){
        return {
            messages: this.prop_messages_list,
            messages_load: false,
            page: 2,
            next_page: this.prop_next_page,
            scroll_top: 0,
            message_block_height: 0,
        }
    },
    computed: {
        ...mapGetters([
            'user'
        ]),
    },
    watch: {
        messages(newVal, oldVal){
            const countNewMessages = newVal.length - oldVal.length;
            this.scroll_top = countNewMessages * this.message_block_height;
        }
    },
    methods: {
        loadMessages(){
            const chatId = this.$route.params.id
            this.messages_load = true;
            api.get(`/chat/${chatId}/messages?page=${this.page}`)
                .then(res => {

                    this.messages = [...res.data.reverse(), ...this.messages];

                    this.messages_load = false;

                    if(res.next_page_url){
                        this.page = this.page + 1;
                        this.next_page = true;
                    }else{
                        this.next_page = false;
                    }
                })
        },
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
                this.loadMessages();
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


        },
        loadMessagesScroll(){
            const container = this.$refs.messages_container;

            container.scrollTo({
                top: this.scroll_top,
            });
        },
        getMessageBlockHeight(){
            const containerMessagesList = this.$refs.messages_list_container;

            this.message_block_height = containerMessagesList.clientHeight / this.messages.length;
        }
    },
    mounted() {
        this.setUpInterSectionObserver();
        this.scrollToBottom();
        this.getMessageBlockHeight();
    },
    updated() {
        this.loadMessagesScroll();
    }
}
</script>

<style scoped>

</style>
