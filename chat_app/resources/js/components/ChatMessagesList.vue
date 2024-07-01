<template>
  <div
    ref="refMessagesContainer"
    class="chat-messages p-4"
  >
    <div
      v-show="isNextPage"
      ref="refNotificationLoading"
      class="text-center py-3"
    >
      Загрузка...
    </div>
    <div ref="refMessagesListContainer">
      <div v-for="message in messages" :key="message.id">
        <ChatMessageItem
          v-if="message.user.id === authUser?.id"
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
import { ref, computed, watch, onMounted, nextTick, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthUserStore } from '@/store/auth_user.js';
import { usePageStore } from '@/store/page.js';
import { loadChatMessages } from '@/services/chat_service.js';

import ChatMessageItem from '@/components/ChatMessageItem.vue';
import ChatMessageItemLeft from '@/components/ChatMessageItemLeft.vue';

const props = defineProps({
  messages: { type: Array, default: [] },
  isNextPage: { type: Boolean, default: false },
});

const route = useRoute();
const authUserStore = useAuthUserStore();
const pageStore = usePageStore();
const refNotificationLoading = ref(null);
const refMessagesContainer = ref(null);
const refMessagesListContainer = ref(null);
const authUser = computed(() => authUserStore.auth_data);
const page = ref(1);
const scrollTop = ref(0);
const isLoading = ref(false);

const currentPage = computed(() => {
  return pageStore.page.pagination.current_page;
});

const loadMessages = async () => {
  if(isLoading.value){
    return;
  }
  const chatId = route.params.id;
  isLoading.value = true;
  // chatMessagesStore.is_loading_messages = true;
  try {
    const res = await loadChatMessages(chatId, currentPage.value + 1);

    const currentHeight = refMessagesListContainer.value.getBoundingClientRect().height;
    pageStore.page.messages = [...res.messages, ...pageStore.page.messages];
    pageStore.page.pagination = res.pagination;

    nextTick(() => {
      const newHeight = refMessagesListContainer.value.getBoundingClientRect().height;
      const currentScrollTop = refMessagesContainer.value.scrollTop;
      const diffHeight = (newHeight - currentHeight) + currentScrollTop;
      refMessagesContainer.value.scrollTo({
        top: diffHeight,
      });
    });

    if(res.pagination.next_page_url){
      chatMessagesStore.chat_id = null;
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
    margin: '10px',
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

watch(
  () => pageStore.page.messages,
  (newVal, oldVal) => {
    if(!newVal){
      return
    }
    const newLastMessage = newVal.slice(-1)?.[0];
    const oldLastMessage = oldVal.slice(-1)?.[0];
    if(oldLastMessage && newLastMessage && newLastMessage.id > oldLastMessage.id){
      nextTick(() => {
        scrollToBottom('smooth');
      });
    }
  }
);

onMounted(() => {
  scrollToBottom();
  setUpInterSectionObserver();
});
</script>

<style scoped>

</style>
