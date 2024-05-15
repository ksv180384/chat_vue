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
            <input v-model="formChat.title"
                   @keydown.enter="saveChat"
                   ref="refInputTitle"
                   type="text"
                   class="form-control"
                   :disabled="isBtnAddChatDisabled"
            />
        </div>

        <template v-slot:footer>
            <button
                @click="saveChat"
                type="button"
                class="btn btn-primary"
                :disabled="isBtnAddChatDisabled"
            >
                Сохранить
            </button>
            <button
                ref="refCloseModalCreateChat"
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
            >
                Отмена
            </button>
        </template>
    </WModal>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useRoomsStore } from '@/store/chats_rooms.js';
import { addChat } from '@/services/chat_service.js';
import { responseErrorNote } from '@/helpers/helpers.js';

import BtnModal from '@/components/modal/BtnModal.vue';
import WModal from '@/components/modal/WModal.vue';
import ChatItem from '@/components/ChatItem.vue';


const roomsStore = useRoomsStore();
const chats = computed(() => roomsStore.chats);
const refCloseModalCreateChat = ref(null);
const modalAddUserChat = ref(null);
const refInputTitle = ref(null);
const router = useRouter();

const formChat = reactive({
    title: '',
});
const isBtnAddChatDisabled = ref(false);

const saveChat = async () => {
    isBtnAddChatDisabled.value = true;

    try {
        const resAddChat = await addChat(formChat);
        refCloseModalCreateChat.value.click();
        router.push(`/chat/${resAddChat.id}`);
    } catch (e) {
        console.error(e);
        responseErrorNote(e);
    } finally {
        isBtnAddChatDisabled.value = false;
    }
}

const focusAfterShownModal = () => {
    refInputTitle.value.focus();
}

const afterHideModal = () => {
    formChat.title = '';
}


onMounted(async () => {
    // bootstrap модальное окно
    modalAddUserChat.value = document.getElementById('modalCreateChat');
    modalAddUserChat.value.addEventListener('shown.bs.modal', focusAfterShownModal);
    modalAddUserChat.value.addEventListener('hide.bs.modal', afterHideModal);

    //Сокеты
    // this.$store.state.socket.on('countMessages', function(data){
    //     this.$store.commit('pushChats', data);
    // }.bind(this));
});

onUnmounted(() => {
    modalAddUserChat.value.removeEventListener('shown.bs.modal', focusAfterShownModal);
    modalAddUserChat.value.removeEventListener('hide.bs.modal', afterHideModal);
});

// export default {
//     components: {
//         ChatItem,
//         WModal,
//         BtnModal
//     },
//     data(){
//         return {
//             chatName: '',
//             btn_add_chat_is_disabled: false
//         }
//     },
//     computed: {
//         ...mapGetters('storeChatsList', ['chats']),
//         ...mapGetters('storeUser', {user_id: 'id'})
//     },
//     mounted() {
//         // bootstrap модальное окно
//         this.modalAddUserChat = document.getElementById('modalCreateChat');
//         this.modalAddUserChat.addEventListener('shown.bs.modal', this.focusAfterShownModal);
//         this.modalAddUserChat.addEventListener('hide.bs.modal', this.afterHideModal);
//
//         //Сокеты
//         this.$store.state.socket.on('countMessages', function(data){
//             this.$store.commit('pushChats', data);
//         }.bind(this));
//     },
//     unmounted() {
//         // bootstrap модальное окно
//         this.modalAddUserChat.removeEventListener('shown.bs.modal', this.focusAfterShownModal);
//         this.modalAddUserChat.removeEventListener('hide.bs.modal', this.afterHideModal);
//     },
//     methods: {
//         ...mapMutations('storeChatsList', ['pushChats']),
//         async saveChat(){
//             this.btn_add_chat_is_disabled = true;
//
//             try {
//                 const resAddChat = await addChat({ title: this.chatName }, this.user_id);
//                 this.pushChats(resAddChat.chat);
//                 this.btn_add_chat_is_disabled = false;
//                 this.$refs.closeModalCreateChat.click();
//                 router.push(`/chat/${resAddChat.chat.id}`);
//             }catch (e) {
//                 this.btn_add_chat_is_disabled = false;
//                 responseErrorNote(e);
//             }
//         },
//         focusAfterShownModal(){
//             this.$refs.input_title_chat.focus();
//         },
//         afterHideModal(){
//             this.chatName = '';
//         }
//     },
// }
</script>
