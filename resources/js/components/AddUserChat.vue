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
                   v-model.trim="search_user"
                   type="text"
                   class="form-control"
                   ref="input_search"
            />
        </div>

        <div>
            <div v-if="loading" class="text-center p-5">
                Поиск пользователей...
            </div>
            <SearchUserItem v-else
                            v-for="user in users"
                            :key="user.id"
                            :user="user"
                            :active="user.id === select_user"
                            @on-change-active="changeSelectUser"
            />
        </div>

        <template v-slot:footer>
            <button @click="joinUser"
                    type="button"
                    class="btn btn-primary"
                    :disabled="btn_join_is_disabled || select_user === 0"
            >
                Добавить
            </button>
            <button ref="closeModalAddUserChat"
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
            >
                Отмена
            </button>
        </template>
    </WModal>
</template>

<script>

import { mapMutations } from "vuex";

import BtnModal from "./modal/BtnModal";
import WModal from "./modal/WModal";
import SearchUserItem from "./SearchUserItem";

import { joinUserToChat, searchUserToChat } from "../services/chat_service";


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
            select_user: 0,
            search_user: '',
            searchTimeout: null,
            loading: false,
            btn_join_is_disabled: false,
        }
    },
    methods: {
        ...mapMutations('storeChatsList', ['pushChats']),
        ...mapMutations('storeChat', ['setUsers']),
        search(){
            if(this.search_user.length < 2){
                this.loading = false;
                this.users = [];
                return true;
            }

            this.loading = true;

            clearTimeout(this.searchTimeout);

            this.searchTimeout = setTimeout(function () {
                this.searchUserRequest();
            }.bind(this), 1000);
        },
        async searchUserRequest(){
            try {
                const resSearchUserToChat = await searchUserToChat(this.search_user);
                this.users = resSearchUserToChat.users;
                //this.pushChats(resSearchUserToChat.chat);
                this.loading = false;
            }catch (e) {
                this.loading = false;
            }
        },
        async joinUser(){
            try {
                this.btn_join_is_disabled = true;
                const resJoinUserToChat =  await joinUserToChat({ chat_room_id: this.chat_id, user_id: this.select_user });
                //this.setUsers(resJoinUserToChat.chat.users);
                this.$refs.closeModalAddUserChat.click();
                this.btn_join_is_disabled = false;
            }catch (e) {
                this.btn_join_is_disabled = false;
            }
        },
        changeSelectUser(id){
            this.select_user = id;
        },
        focusAfterShownModal(){
            this.$refs.input_search.focus();
        },
        afterHideModal(){
            this.users = [];
            this.search_user = '';
            this.select_user = 0;
        }
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
