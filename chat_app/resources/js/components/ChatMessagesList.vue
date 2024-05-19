<template>
  <div
    ref="refMessagesContainer"
    class="chat-messages p-4"
  >
    <div
      v-if="chatMessagesStore.isNextPage"
      ref="refNotificationLoading"
      class="text-center py-3"
    >
      Загрузка...
    </div>
    <div ref="messages_list_container">
      <div v-for="message in chatMessagesStore.messages" :key="message.id">
        <ChatMessageItem
          v-if="message.user.id === authUser.id"
          :message="message"
        />
        <ChatMessageItemLeft
          v-else
          :message="message"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import {ref, computed, watch, onMounted, nextTick, onUnmounted} from 'vue';
import { useRoute } from 'vue-router';
import { useAuthUserStore } from '@/store/auth_user.js';
import { useChatMessagesStore } from '@/store/chat_messages.js';
import { loadChatMessages } from '@/services/chat_service.js';

import ChatMessageItem from '@/components/ChatMessageItem.vue';
import ChatMessageItemLeft from '@/components/ChatMessageItemLeft.vue';

const props = defineProps({
  isNextMessages: { type: Boolean, default: false },
});

const route = useRoute();
const authUserStore = useAuthUserStore();
const chatMessagesStore = useChatMessagesStore();
const refNotificationLoading = ref(null);
const refMessagesContainer = ref(null);
const authUser = authUserStore.auth_user;
const page = ref(1);
const scrollTop = ref(0);
const messageBlockHeight = ref(0);
const isLoading = ref(false);

const loadMessages = async () => {
  if(isLoading.value){
    return;
  }
  const chatId = route.params.id;
  isLoading.value = true;
  chatMessagesStore.is_loading_messages = true;
  try {
    const res = await loadChatMessages(chatId, page.value + 1);
    chatMessagesStore.push(res.messages.reverse());
    page.value = res.pagination.current_page;

    if(res.pagination.next_page_url){
      chatMessagesStore.incrementPage();
      chatMessagesStore.isNextPage = true;
    }else{
      chatMessagesStore.isNextPage = false;
    }
  } catch (e) {
    console.error(e);
  } finally {
    isLoading.value = false;
  }
}

const setUpInterSectionObserver = () => {
  let options = {
    // root: refMessagesContainer.value,
    margin: "10px",
  };
  const listEndObserver = new IntersectionObserver(
    ([entry]) => {
      if (entry.isIntersecting) {
        loadMessages();
      }
  },
    options
  );

  listEndObserver.observe(refNotificationLoading.value);
}

const scrollToBottom = (type) => {
  type = type || 'auto';

  refMessagesContainer.value.scrollTo({
    top: refMessagesContainer.value.scrollHeight,
    behavior: type
  });
}

const loadMessagesScroll = () => {
  refMessagesContainer.value.scrollTo({
    top: scrollTop.value,
  });
}

const getMessageBlockHeight = () => {
  messageBlockHeight.value = refMessagesContainer.value.clientHeight / chatMessagesStore.messages.length;
}

watch(
  () => chatMessagesStore.messages,
  (newVal, oldVal) => {
    nextTick(() => {
      if(chatMessagesStore.is_loading_messages){
        chatMessagesStore.is_loading_messages = false;
        const countNewMessages = newVal.length - oldVal.length;
        scrollTop.value = countNewMessages * messageBlockHeight.value;
        loadMessagesScroll();
        return;
      }
      scrollToBottom('smooth');
    });
  }
);

onMounted(() => {
  chatMessagesStore.chat_id = parseInt(route.params.id);
  setUpInterSectionObserver();
  scrollToBottom();
  getMessageBlockHeight();
});

onUnmounted(() => {
  chatMessagesStore.chat_id = null;
});
</script>

<style scoped>

</style>
