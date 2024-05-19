<template>
  <main class="content">
    <div class="container p-0">
      <div
        v-if="!isSocketConnected"
        class="is-socket-connect-container"
      >
        Отсутствует соединение с сервером
      </div>
      <Header/>
      <Suspense>
        <router-view />
        <template #fallback>
          Loading...
        </template>
      </Suspense>
    </div>
    <!--        <div v-if="is_site_not_work" class="site-not-work-container">-->
    <!--            <div>Чат временно не работает</div>-->
    <!--        </div>-->
  </main>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import { useRoomsStore } from '@/store/chats_rooms.js';
import { useSocketStore } from '@/store/socket.js';
import { loadUserChats } from '@/services/chat_service.js';

import Header from '@/components/Header.vue';

const store = useStore();
const roomsStore = useRoomsStore();
const socketStore = useSocketStore();

onMounted(async () => {
  await loadChats();
});

const currentUserId = computed(() => store.state.storeUser.id);
const isSocketConnected = computed(() => socketStore.socket.connected);

const loadChats = async () => {
  try {
    const resChatsList = await loadUserChats();

    roomsStore.setChats(resChatsList);
    socketStore.socket.io.opts.query = {
      user_id: currentUserId,
      chats: resChatsList?.map(item => item),
    };
    socketStore.socket.connect();
  }catch(e){
    console.error('error load chats', e);
  }
}
</script>

<style scoped>
.site-not-work-container{
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  font-size: 32px;
  background-color: #fff;
  display: flex;
  justify-content: center;
  align-items: center;
}

.site-not-work-container > div{
  text-align: center;
}

.is-socket-connect-container{
  text-align: center;
  padding: 6px 0;
  background-color: #ff4848;
  color: white;
}
</style>
