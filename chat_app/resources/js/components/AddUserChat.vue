<template>
  <BtnModal
    ref="modalAddUserChat"
    title="Добавить пользователя"
    modal="modalAddUserChat"
    variant="primary"
  >
    +
  </BtnModal>

  <WModal id="modalAddUserChat">
    <template v-slot:title>
      Добавить пользователя в чат
    </template>
    <div class="mb-3">
      <label class="form-label">Найти пользователя</label>
      <input
        v-model.trim="search_user"
        ref="input_search"
        type="text"
        class="form-control"
        @keyup="search"
      />
    </div>

    <div>
      <div v-if="loading" class="text-center p-5">
        Поиск пользователей...
      </div>
      <template v-else>
        <SearchUserItem
          v-for="user in users"
          :key="user.id"
          :user="user"
          :active="user.id === select_user"
          @on-change-active="changeSelectUser"
        />
      </template>
    </div>

    <template v-slot:footer>
      <button
        type="button"
        class="btn btn-primary"
        :disabled="btn_join_is_disabled || select_user === 0"
        @click="joinUser"
      >
        Добавить
      </button>
      <button
        ref="closeModalAddUserChat"
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
import { mapMutations } from 'vuex';

import BtnModal from '@/components/modal/BtnModal.vue';
import WModal from '@/components/modal/WModal.vue';
import SearchUserItem from '@/components/SearchUserItem.vue';

import { joinUserToChat, searchUserToChat } from '@/services/chat_service.js';
import { responseErrorNote } from '@/helpers/helpers.js';


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
        this.loading = false;
      }catch (e) {
        this.users = [];
        this.loading = false;
        responseErrorNote(e);
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
        this.select_user = 0;
        responseErrorNote(e);
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
