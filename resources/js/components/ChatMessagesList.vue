<template>
    <div class="chat-messages p-4" ref="messages_container">
        <div v-if="messages">
            <div v-show="next" ref="sentinel" class="text-center py-3">Загрузка...</div>
            <div ref="messages_list_container">
                <div v-for="message in messages" :key="message.id">
                    <ChatMessageItem v-if="message.user.id === current_user_id" :message="message"/>
                    <ChatMessageItemLeft v-else :message="message"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {mapGetters, mapMutations} from "vuex";

import ChatMessageItem from "../components/ChatMessageItem";
import ChatMessageItemLeft from "../components/ChatMessageItemLeft";

import { loadChatMessages } from '../services/chat_service';

export default {
    name: "ChatMessagesList",
    components: {
        ChatMessageItemLeft,
        ChatMessageItem,
    },
    data(){
        return {
            scroll_top: 0,
            message_block_height: 0,
        }
    },
    computed: {
        ...mapGetters('storeChat', ['messages', 'page', 'next', 'load', 'add_messages_type']),
        ...mapGetters({current_user_id: 'storeUser/id'}),
    },
    watch: {
        messages(newVal, oldVal){
            const countNewMessages = newVal.length - oldVal.length;
            this.scroll_top = countNewMessages * this.message_block_height;
        }
    },
    methods: {
        ...mapMutations('storeChat', ['unshiftMessages', 'setPage', 'setNext', 'setLoad', 'setAddMessagesType']),
        async loadMessages(){
            const chatId = this.$route.params.id

            const resLoadMessages = await loadChatMessages(chatId, this.page);
            this.setAddMessagesType('load');
            this.unshiftMessages(resLoadMessages.data.reverse());

            if(resLoadMessages.next_page_url){
                this.setPage(this.page + 1);
                this.setNext(true);
            }else{
                this.setNext(false);
            }
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

            if(this.add_messages_type === 'send'){
                this.scrollToBottom('smooth');
                return true;
            }

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
