<template>
  <div
    v-if="messageNotificationsStore.notifications"
    class="notification-message-list"
  >
    <div
      v-for="notification of messageNotificationsStore.notifications"
      class="notification-message-item"
      @click="toPageChat(notification.chat_room_id, notification.id)"
    >
      <img :src="notification.user.avatar_src" class="avatar"/>
      <div class="content">
        <div class="user-name">{{ notification.user.name }}</div>
        <div class="message">
          {{ notification.message }}
        </div>
      </div>
      <div @click.stop="close(notification.id)" class="close">
        x
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useRoomsStore } from '@/store/chats_rooms.js';
import { useSocketStore } from '@/store/socket.js'
import { useMessageNotificationsStore } from '@/store/message_notifications.js'

const roomsStore = useRoomsStore();
const socketStore = useSocketStore();
const messageNotificationsStore = useMessageNotificationsStore();
const route = useRoute();
const router = useRouter();

const toPageChat = (chatId, messageId) => {
  router.push({ name: 'chat', params: { id: chatId } });
  messageNotificationsStore.popNotification(messageId);
}

const close = (messageId) => {
  messageNotificationsStore.popNotification(messageId);
}

onMounted(() => {
  socketStore.socket.on('message', (data) => {

    const chatId = data.chat_room_id;
    const currentChat = roomsStore.chats.find(item => item.id === chatId);

    if(
      currentChat.settings?.show_notification_new_message &&
      !(route.name === 'chat' && +route.params.id === chatId)
    ){
      messageNotificationsStore.pushNotification(data);

      setTimeout(() => {
        messageNotificationsStore.popNotification(data.id);
      }, 8000);
    }

  });
});
</script>

<style scoped>
    .notification-message-list{
        position: fixed;
        display: flex;
        flex-direction: column;
        bottom: 10px;
        right: 10px;
        z-index: 99;
    }

    .notification-message-item{
        position: relative;
        display: flex;
        flex-direction: row;
        border-radius: 4px;
        background-color: #6ec167;
        color: #f3fff4;
        width: 280px;
        padding: 6px 8px;
        margin-top: 10px;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 10%), 0 8px 10px -6px rgb(0 0 0 / 10%);
    }

    .avatar{
        width: 64px;
        height: 64px;
        border-radius: 4px;
        margin-right: 10px;
        object-fit: cover;
        object-position: top;
    }

    .content{
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .user-name{
        font-weight: bold;
    }

    .message{
        font-size: 14px;
    }

    .close{
        position: absolute;
        top: 4px;
        right: 4px;
        background-color: red;
        width: 24px;
        height: 24px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 4px;
        cursor: pointer;
        text-transform: uppercase;
        font-weight: bold;
    }
</style>
