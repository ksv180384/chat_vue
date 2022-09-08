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
                   :disabled="btn_add_chat_is_disabled"
            />
        </div>

        <template v-slot:footer>
            <button @click="saveChat"
                    type="button"
                    class="btn btn-primary"
                    :disabled="btn_add_chat_is_disabled"
            >
                Сохранить
            </button>
            <button ref="closeModalCreateChat" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
        </template>
    </WModal>
</template>

<script>

import {mapGetters, mapMutations} from "vuex";

import router from "../../router";

import BtnModal from "../../components/modal/BtnModal";
import WModal from "../../components/modal/WModal";
import ChatItem from "../../components/ChatItem";
import { addChat } from "../../services/chat_service";


export default {
    components: {
        ChatItem,
        WModal,
        BtnModal
    },
    data(){
        return {
            chatName: '',
            btn_add_chat_is_disabled: false
        }
    },
    computed: {
        ...mapGetters('storeChatsList', ['chats']),
    },
    mounted() {
        //this.loadChats();

        // bootstrap модальное окно
        this.modalAddUserChat = document.getElementById('modalCreateChat');
        this.modalAddUserChat.addEventListener('shown.bs.modal', this.focusAfterShownModal);
        this.modalAddUserChat.addEventListener('hide.bs.modal', this.afterHideModal);

        //Сокеты
        this.$store.state.socket.on('countMessages', function(data){
            this.$store.commit('pushChats', data);
        }.bind(this));
    },
    unmounted() {
        // bootstrap модальное окно
        this.modalAddUserChat.removeEventListener('shown.bs.modal', this.focusAfterShownModal);
        this.modalAddUserChat.removeEventListener('hide.bs.modal', this.afterHideModal);
    },
    methods: {
        ...mapMutations('storeChatsList', ['pushChats']),
        async saveChat(){
            this.btn_add_chat_is_disabled = true;
            const resAddChat = await addChat({ title: this.chatName });
            this.pushChats(resAddChat.chat);
            this.btn_add_chat_is_disabled = false;
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
}
</script>
