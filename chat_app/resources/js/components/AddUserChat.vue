<template>
  <BtnModal
    ref="modalAddUserChat"
    modal-target-id="idModalAddUserChat"
    title="Добавить пользователя"
    variant="primary"
    type="primary"
  >
    +
  </BtnModal>

  <WModal id="idModalAddUserChat">
    <template #title>
      Добавить пользователя в чат
    </template>
    <div class="mb-3">
      <label class="form-label">Найти пользователя</label>
      <input
        v-model.trim="searchUser"
        ref="refInputSearch"
        type="text"
        class="form-control"
        @input="search"
      />
    </div>

    <div>
      <div v-if="isLoadingSearch" class="text-center p-5">
        Поиск пользователей...
      </div>
      <template v-else>
        <SearchUserItem
          v-for="user in users"
          :key="user.id"
          :user="user"
          :active="user.id === selectUserId"
          @on-change-active="changeSelectUser"
        />
      </template>
    </div>

    <template #footer>
      <button
        type="button"
        class="btn btn-primary"
        :disabled="isDisabled || selectUserId === 0"
        @click="joinUser"
      >
        Добавить
      </button>
      <button
        ref="refCloseModalAddUserChat"
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
import { ref, onMounted, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';
import { responseErrorNote } from '@/helpers/helpers.js';
import { joinUserToChat, searchUserToChat } from '@/services/chat_service.js';

import BtnModal from '@/components/modal/BtnModal.vue';
import WModal from '@/components/modal/WModal.vue';
import SearchUserItem from '@/components/SearchUserItem.vue';

const route = useRoute();
const modalAddUserChat = ref(null);
const idModalAddUserChat = ref(null);
const refInputSearch = ref(null);
const refCloseModalAddUserChat = ref(null);
const searchTimeout = ref(null);
const chatId = ref(route.params.id);
const users = ref([]);
const selectUserId = ref(0);
const searchUser = ref('');
const isLoadingSearch = ref(false);
const isDisabled = ref(false);

const search = () => {
  if(searchUser.value.length < 2){
    // isLoadingSearch.value = false;
    users.value = [];
    return true;
  }

  clearTimeout(searchTimeout.value);

  searchTimeout.value = setTimeout(() => {
    searchUserRequest();
  }, 500);
}

const searchUserRequest = async () => {
  isLoadingSearch.value = true;
  try {
    const resSearchUserToChat = await searchUserToChat(searchUser.value);
    users.value = resSearchUserToChat.users;
    console.log(users.value)
  }catch (e) {
    users.value = [];
    responseErrorNote(e);
  } finally {
    isLoadingSearch.value = false;
  }
}

const joinUser = async () => {
  try {
    isDisabled.valus = true;
    const resJoinUserToChat =  await joinUserToChat({ chat_room_id: chatId.value, user_id: selectUserId.value });
    //this.setUsers(resJoinUserToChat.chat.users);
    refCloseModalAddUserChat.value.click();
  }catch (e) {
    selectUserId.value = 0;
    responseErrorNote(e);
  } finally {
    isDisabled.value = false;
  }
}

const changeSelectUser =(id) => {
  selectUserId.value = id;
}

const focusAfterShownModal = () => {
  refInputSearch.value.focus();
}

const afterHideModal = () => {
  users.value = [];
  searchUser.value = '';
  selectUserId.value = 0;
}

onMounted(() => {
  idModalAddUserChat.value = document.getElementById('idModalAddUserChat');
  idModalAddUserChat.value.addEventListener('shown.bs.modal', focusAfterShownModal);
  idModalAddUserChat.value.addEventListener('hide.bs.modal', afterHideModal);
});

onUnmounted(() => {
  idModalAddUserChat.value.removeEventListener('shown.bs.modal', focusAfterShownModal);
  idModalAddUserChat.value.removeEventListener('hide.bs.modal', afterHideModal);
});

/*
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
 */
</script>
