<template>
    <BtnModal title="Добавить пользователя"
              modal="modalAddUserChat"
              variant="primary"
              ref="modalAddUserChat"
    >+</BtnModal>

    <WModal id="modalAddUserChat">
        <template v-slot:title>
            Добавить пользователя в чат
        </template>
        <div class="mb-3">
            <label class="form-label">Найти пользователя</label>
            <input @keyup="search"
                   v-model="searchUser"
                   type="text"
                   class="form-control"
                   ref="input_search"
            />
        </div>

        <div>
            <SearchUserItem v-for="user in users"
                      :key="user.id"
                      :user="user"
                      :active="user.id === active"
                      v-on:change-active="changeActive"
            />
        </div>

        <template v-slot:footer>
            <button @click="joinUser" type="button" class="btn btn-primary">Добавить</button>
            <button ref="closeModalAddUserChat"
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
            >Отмена</button>
        </template>
    </WModal>
</template>

<script>

import {mapMutations} from "vuex";

import api from "./../helpers/api";

import BtnModal from "./modal/BtnModal";
import WModal from "./modal/WModal";
import SearchUserItem from "./SearchUserItem";


export default {
    name: 'AddUserChat',
    components: {
        SearchUserItem,
        WModal,
        BtnModal
    },
    data(){
        return {
            chat_id: this.$route.params.id,
            modalAddUserChat: null,
            showM: false,
            users: [],
            active: 0,
            searchUser: '',
            searchTimeout: null,
            loading: false,
        }
    },
    methods: {
        ...mapMutations('storeChatsList', ['pushChats']),
        search(){
            clearTimeout(this.searchTimeout);

            this.searchTimeout = setTimeout(function () {
                this.searchUserRequest();
            }.bind(this), 1000);
        },
        searchUserRequest(){
            if(this.searchUser.length < 2){
                return true;
            }
            api.get('/users/search/' + this.searchUser)
                .then(res => {
                    this.users = res.users;
                    this.loading = false;
                    this.pushChats(res.chat)
                }).catch(error => {
                // handle error
                this.loading = false;
                this.error = error.response.data.message;
            });
        },
        changeActive(id){
            this.active = id;
        },
        joinUser(){
            api.post('/chat/join', { chat_room_id: this.chat_id, user_id: this.active })
                .then(res => {
                    this.$store.commit('setChatUsers', res.chat.users);
                    this.$refs.closeModalAddUserChat.click();
                    this.loading = false;
                }).catch(error => {
                    // handle error
                    this.loading = false;
                    this.error = error.response.data.message;
                });
        },
        focusAfterShownModal(){
            this.$refs.input_search.focus();
        },
        afterHideModal(){
            this.users = [];
            this.searchUser = '';
        }
    },
    computed: {
        chat(){
            return this.$store.getters.chat;
        },
    },
    mounted() {
        this.modalAddUserChat = document.getElementById('modalAddUserChat');
        this.modalAddUserChat.addEventListener('shown.bs.modal', this.focusAfterShownModal);
        this.modalAddUserChat.addEventListener('hide.bs.modal', this.afterHideModal);
    },
    unmounted() {
        this.modalAddUserChat.removeEventListener('shown.bs.modal', this.focusAfterShownModal);
        this.modalAddUserChat.removeEventListener('hide.bs.modal', this.afterHideModal);
    }
}
</script>

<style scoped>

</style>
