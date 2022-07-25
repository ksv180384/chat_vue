<template>
    <h1 class="h1 mb-3 d-flex flex-row justify-content-between">
        <div>Чаты</div>
        <div>
            <BtnModal modal="modalCreateChat" variant="primary" size="sm">Добавить чат</BtnModal>
        </div>
    </h1>

    <div v-if="chats" class="card">
        <template v-for="chat in chats" :key="chat.id" >
            <ChatItem :chat="chat"/>
        </template>
    </div>

    <WModal id="modalCreateChat">
        <template v-slot:title>
            Добавить чат
        </template>
        <div class="mb-3">
            <label class="form-label">Название чата</label>
            <input v-model="chatName"
                   @keydown.enter="saveChat"
                   ref="input_title_chat"
                   type="text"
                   class="form-control"
            />
        </div>

        <template v-slot:footer>
            <button @click="saveChat" type="button" class="btn btn-primary">Сохранить</button>
            <button ref="closeModalCreateChat" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
        </template>
    </WModal>
</template>

<script>

import {mapGetters, mapMutations} from "vuex";

import router from "../router";
import api from "../helpers/api";

import BtnModal from "../components/modal/BtnModal";
import WModal from "../components/modal/WModal";
import ChatItem from "../components/ChatItem";
import {loadChatListPage, addChat} from "../services/chat_service";


export default {
    components: {
        ChatItem,
        WModal,
        BtnModal
    },
    data(){
        return {
            chatName: '',
        }
    },
    computed: {
        ...mapGetters('storeChatsList', ['chats']),
    },
    mounted() {
        this.loadChats();

        // bootstrap модальное окно
        this.modalAddUserChat = document.getElementById('modalCreateChat');
        this.modalAddUserChat.addEventListener('shown.bs.modal', this.focusAfterShownModal);
        this.modalAddUserChat.addEventListener('hide.bs.modal', this.afterHideModal);

        //Сокеты
        this.$store.state.socket.on('countMessages', function(data){
            this.$store.commit('pushChats', data);
        }.bind(this));
    },
    methods: {
        ...mapMutations('storeChatsList', ['setChats']),
        async loadChats(){
            const resChatsList = await loadChatListPage();
            this.setChats(resChatsList.chats);
        },
        async saveChat(){
            const resAddChat = await addChat({ title: this.chatName });
            this.$store.commit('pushChats', resAddChat.chat);
            this.$refs.closeModalCreateChat.click();
            router.push(`/chat/${resAddChat.chat.id}`);
        },
        focusAfterShownModal(){
            this.$refs.input_title_chat.focus();
        },
        afterHideModal(){
            this.chatName = '';
        }
    },
    unmounted() {
        // bootstrap модальное окно
        this.modalAddUserChat.removeEventListener('shown.bs.modal', this.focusAfterShownModal);
        this.modalAddUserChat.removeEventListener('hide.bs.modal', this.afterHideModal);
    }
}
</script>
