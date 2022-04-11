<template>
    <h1 class="h1 mb-3 d-flex flex-row justify-content-between">
        <div>Чаты</div>
        <div>
            <BtnModal modal="modalAddChat" variant="primary" size="sm">Добавить чат</BtnModal>
        </div>
    </h1>

    <div v-if="chats" class="card">
        <template v-for="chat in chats" :key="chat.id" >
            <ChatItem :chat="chat"/>
        </template>
    </div>

    <WModal id="modalAddChat">
        <template v-slot:title>
            Добавить чат
        </template>
        <div class="mb-3">
            <label class="form-label">Название чата</label>
            <input v-model="chatName" type="text" class="form-control" />
        </div>

        <template v-slot:footer>
            <button @click="saveChat" type="button" class="btn btn-primary">Сохранить</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
        </template>
    </WModal>
</template>

<script>

import BtnModal from "../components/modal/BtnModal";
import WModal from "../components/modal/WModal";
import ChatItem from "../components/ChatItem";
import api from "../helpers/api";

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
                    this.$store.commit('addChat', res.chat)
                }).catch(error => {
                // handle error
                this.request = false;
                this.error = error.response.data.message;
            });
        }
    },
    mounted() {
        this.$store.dispatch('loadChats');
    }
}
</script>
