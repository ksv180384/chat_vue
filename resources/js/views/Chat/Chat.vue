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
                    <li>
                        <router-link :to="`/chat/${chat_id}/settings`" class="dropdown-item">Настройки</router-link>
                    </li>
                    <li class="dropdown-divider"></li>
                    <li>
                        <a @click.prevent="leave" href="#" class="dropdown-item">Покинуть чат</a>
                    </li>
                    <li>
                        <a v-if="user_id === chat.creator.id" @click.prevent="del"
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
                <SearchUserChat v-model="search_user_text" :show_btn_join_user="user_id === chat.creator_id"/>
                <ChatUsersList :chat_users="usersList"
                               :chat_creator_id="chat.creator_id"
                />
            </div>
            <div class="col-12 col-lg-9 col-xl-9">
                <div class="position-relative">
                    <ChatMessagesList/>
                </div>
                <SendMessage :chat_id="chat.id"/>
            </div>
        </div>
    </div>
</template>

<script>

import {mapGetters, mapMutations} from 'vuex';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faCaretLeft } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

import router from "../../router";

import ChatMessagesList from "../../components/ChatMessagesList";
import ChatUsersList from "../../components/ChatUsersList";
import SearchUserChat from "../../components/SearchUserChat";
import SendMessage from "../../components/SendMessage";
import {deleteChat, loadChatPage, laveChat} from "../../services/chat_service";
import {responseErrorNote} from "../../helpers/helpers";

library.add(faCaretLeft);

export default {
    name: 'Chat',
    components: {
        ChatMessagesList,
        SendMessage,
        SearchUserChat,
        ChatUsersList,
        FontAwesomeIcon
    },
    data(){
        return {
            chat_id: this.$route.params.id,
            search_user_text: '',
        }
    },
    mounted() {
        this.loadChat();
    },
    unmounted() {
        this.setChat(null);
        this.setUsers([]);
        this.setMessages([]);
        this.setPage(1);
    },
    computed: {
        ...mapGetters({ user_id: 'storeUser/id' }),
        ...mapGetters(
            'storeChat',
            ['chat', 'users', 'page']
        ),
        usersList(){
            if(this.search_user_text.trim().length === 0){
                return this.users;
            }
            return this.users.filter((item) => item.name.toLowerCase().includes(this.search_user_text.toLowerCase()));
        }
    },
    methods: {
        ...mapMutations(
            'storeChat',
            ['setChat', 'setUsers', 'setMessages', 'setPage', 'setNext', 'setAddMessagesType']
        ),
        ...mapMutations('storeChatsList', ['deleteChat']),
        async leave(){
            try {
                await laveChat(this.chat_id);
                this.deleteChat(this.chat_id);
                router.push('/');
            } catch (e) {
                responseErrorNote(e);
            }
        },
        async del(){
            try {
                await deleteChat(this.chat_id);
                this.deleteChat(this.chat_id);
                router.push('/');
            } catch (e) {
                responseErrorNote(e);
            }
        },
        async loadChat(){
            const pageData = await loadChatPage(this.chat_id);
            this.setChat(pageData.chat);
            this.setUsers(pageData.chat.users);
            this.setAddMessagesType('load');
            this.setMessages(pageData.messages.data.reverse());
            this.setPage(this.page + 1);

            if(pageData.messages.next_page_url){
                this.setNext(true);
            }
        },
    },
}
</script>
