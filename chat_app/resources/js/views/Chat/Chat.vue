<template>
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 my-3">
      <span class="me-3">
        <router-link to="/">
          <FontAwesomeIcon icon="caret-left" />
        </router-link>
      </span>
      <span v-if="chat">{{ chat.title }}</span>
    </h1>

    <div v-if="chat">
      <div class="btn-group" role="group">
        <button
          type="button"
          class="btn btn-light"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 512" height="20px">
            <path d="M64 360C94.93 360 120 385.1 120 416C120 446.9 94.93 472 64 472C33.07 472 8 446.9 8 416C8 385.1 33.07 360 64 360zM64 200C94.93 200 120 225.1 120 256C120 286.9 94.93 312 64 312C33.07 312 8 286.9 8 256C8 225.1 33.07 200 64 200zM64 152C33.07 152 8 126.9 8 96C8 65.07 33.07 40 64 40C94.93 40 120 65.07 120 96C120 126.9 94.93 152 64 152z"/>
          </svg>
        </button>
        <ul class="dropdown-menu">
          <li>
            <router-link
              :to="{ name: 'chat.user-settings', params: { id: chatId } }"
              class="dropdown-item"
            >
              Настройки
            </router-link>
          </li>
          <li class="dropdown-divider"></li>
          <li>
            <a
              href="#"
              class="dropdown-item"
              @click.prevent="leave"
            >
              Покинуть чат
            </a>
          </li>
          <li>
            <a v-if="chatId === chat.creator?.id"
               href="#"
               class="dropdown-item"
               @click.prevent="remove"
            >
              Удалить чат
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div v-if="chat.id" class="card">
    <div class="row g-0">
      <div class="col-12 col-lg-3 col-xl-3 border-right">
        <SearchUserChat
          v-model="searchUserText"
          :show_btn_join_user="chatId === chat.creator_id"
        />
        <ChatUsersList
          :chat_users="usersList"
          :chat_creator_id="chat.creator?.id"
        />
      </div>
      <div class="col-12 col-lg-9 col-xl-9">
        <div class="position-relative">
          <ChatMessagesList/>
        </div>
        <SendMessage :chat-id="chat.id"/>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useRoomsStore } from '@/store/chats_rooms.js';
import { useChatMessagesStore } from '@/store/chat_messages.js';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faCaretLeft } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { deleteChat, loadChatPage, laveChat } from '@/services/chat_service.js';
import { responseErrorNote } from '@/helpers/helpers.js';

import ChatMessagesList from '@/components/ChatMessagesList.vue';
import ChatUsersList from '@/components/ChatUsersList.vue';
import SearchUserChat from '@/components/SearchUserChat.vue';
import SendMessage from '@/components/SendMessage.vue';

library.add(faCaretLeft);

const router = useRouter();
const route = useRoute();
const roomsStore = useRoomsStore();
const chatMessagesStore = useChatMessagesStore();
const chatId = ref(route.params.id);
const searchUserText = ref('');
const chat = ref({});
const users = ref([]);
const isLoading = ref(false);

const usersList = computed(() => {
  if(searchUserText.value.trim().length === 0){
    return users.value;
  }
  return users.value.filter((item) => item.name.toLowerCase().includes(searchUserText.value.toLowerCase()));
})

const loadChat = async () => {
  try {
    const res = await loadChatPage(chatId.value);
    chat.value = res.chat;
    users.value = res.users;
    chatMessagesStore.set(res.messages.reverse());
    chatMessagesStore.isNextPage = !!res.pagination.next_page_url;
  } catch (e) {
    console.error(e);
  } finally {

  }
}

const leave = async () => {
  isLoading.value = true;
  try {
    await laveChat(chatId.value);
    roomsStore.removeChat(chatId.value);
    // this.deleteChat(this.chat_id);
    router.push({ name: 'chats-list' });
  } catch (e) {
    console.error(e);
    responseErrorNote(e);
  } finally {
    isLoading.value = false;
  }
}

const remove = async () => {
  isLoading.value = true;
  try {
    await deleteChat(chatId.value);
    router.push({ name: 'chats-list' });
  } catch (e) {
    console.error(e);
    responseErrorNote(e);
  } finally {
    isLoading.value = false;
  }
}

onMounted(() => {
  loadChat();
});

/*
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
        async remove(){
            try {
                await deleteChat(this.chat_id);
                // this.deleteChat(this.chat_id);
                router.push('/');
            } catch (e) {
                console.error(e);
                responseErrorNote(e);
            }
        },
        async loadChat(){
            const pageData = await loadChatPage(this.chat_id);
            this.setChat(pageData.chat);
            this.setUsers(pageData.chat.users);
            this.setAddMessagesType('load');
            this.setMessages(pageData.messages.reverse());
            this.setPage(this.page + 1);

            if(pageData.messages.next_page_url){
                this.setNext(true);
            }
        },
    },
}
 */
</script>
