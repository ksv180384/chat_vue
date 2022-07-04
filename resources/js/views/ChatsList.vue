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

import BtnModal from "../components/modal/BtnModal";
import WModal from "../components/modal/WModal";
import ChatItem from "../components/ChatItem";
import api from "../helpers/api";
import router from "../router";

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
        chats(){
            return this.$store.getters.chats;
        }
    },
    methods: {
        saveChat(){
            api.post('/chat/create', { title: this.chatName })
                .then(res => {
                    this.request = false;
                    this.$store.commit('addChat', res.chat);
                    this.$refs.closeModalCreateChat.click();
                    router.push(`/chat/${res.chat.id}`);
                }).catch(error => {
                // handle error
                this.request = false;
                this.error = error.response.data.message;
            });
        },
        focusAfterShownModal(){
            this.$refs.input_title_chat.focus();
        },
        afterHideModal(){
            this.chatName = '';
        }
    },
    watch: {
        chats(newChats, oldChats){

            console.log(newChats);

            if (newChats.length)

            for (let chat of newChats){

                console.log(chat);
            }
            //console.log(oldChats);
            //console.log(newChats);
            //this.$store.state.socket.emit('countMessagesRooms', );
        }
    },
    mounted() {
        this.$store.dispatch('loadChats');

        // bootstrap модальное окно
        this.modalAddUserChat = document.getElementById('modalCreateChat');
        this.modalAddUserChat.addEventListener('shown.bs.modal', this.focusAfterShownModal);
        this.modalAddUserChat.addEventListener('hide.bs.modal', this.afterHideModal);

        //Сокеты
        this.$store.state.socket.on('countMessages', function(data){
            console.log(data);
            //this.pushMessage(data);
        }.bind(this));
    },
    unmounted() {
        // bootstrap модальное окно
        this.modalAddUserChat.removeEventListener('shown.bs.modal', this.focusAfterShownModal);
        this.modalAddUserChat.removeEventListener('hide.bs.modal', this.afterHideModal);

        console.log(this.$store.getters.chats);
    }
}
</script>
