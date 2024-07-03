<template>
  <div class="flex-grow-0 py-3 px-4 border-top">
    <div class="input-group">
      <input
        v-model="message"
        type="text"
        class="form-control"
        placeholder="Введите сообщение"
        :disabled="isLoadingSend"
        @keyup.enter="send"
      />
      <button
        class="btn btn-primary"
        :disabled="isLoadingSend"
        @click.prevent="send"
      >
          отправить
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { sendMessage } from '@/services/chat_service.js';

const props = defineProps({
  chatId: { type: Number, default: null },
});

const message = ref('');
const isLoadingSend = ref(false);

const send = async() => {

  isLoadingSend.value = true;
  try {
    if(message.value.length < 2){
      return true;
    }
    const messageData = { message: message.value, chat_room_id: props.chatId };
    await sendMessage(messageData);
    message.value = '';
  } catch (e) {
    console.error(e);
  } finally {
    isLoadingSend.value = false;
  }
}
</script>
