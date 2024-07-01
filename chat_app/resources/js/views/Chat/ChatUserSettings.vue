<template>
  <h1>
    <router-link :to="`/chat/${chatId}`">
      <FontAwesomeIcon icon="caret-left" />
    </router-link>
    Настройка чата
  </h1>
  <div class="settings-container">
    <ul class="list-group">
      <li class="list-group-item">
        <div class="form-check">
          <input
            v-model="formSettings.show_notification_new_message"
            id="checkboxShowNotification"
            class="form-check-input"
            type="checkbox"
            value="show_notification_new_message"
            @change="changeSetting"
          >
          <label class="form-check-label" for="checkboxShowNotification">
            Уведомлять о новых сообщениях
          </label>
        </div>
      </li>
    </ul>
  </div>
</template>

<script setup>
import {computed, reactive, ref} from 'vue';
import { useRoute } from 'vue-router';
import { useRoomsStore } from '@/store/chats_rooms.js';
import { usePageStore } from '@/store/page.js';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faCaretLeft } from '@fortawesome/free-solid-svg-icons';
import { changeSettingsChat } from '@/services/chat_service.js';
import { responseErrorNote } from '@/helpers/helpers.js';

library.add(faCaretLeft);

const route = useRoute();
const roomsStore = useRoomsStore();
const pageStore = usePageStore();
const chatId = ref(route.params.id);
const settings = computed(() => pageStore.page.settings);
const formSettings = reactive({
  show_notification_new_message: !!pageStore.page.settings?.show_notification_new_message,
});

const  changeSetting = async (e) => {
  const settingField = e.target.value;

  try {
    const res = await changeSettingsChat(chatId.value, formSettings);

    roomsStore.changeChatSettingsById(chatId.value, res.settings);
  } catch (e) {
    formSettings[settingField] = !formSettings[settingField];
    responseErrorNote(e);
  }
}
</script>

<style scoped>
.settings-container{
  max-width: 600px;
  margin: 0 auto;
}
</style>
