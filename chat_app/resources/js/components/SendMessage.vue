<template>
  <div class="flex-grow-0 py-3 px-4 border-top">
    <div class="input-group">
      <input v-model="message"
             @keyup.enter="send"
             type="text"
             class="form-control"
             placeholder="Введите сообщение"
             :disabled="send_loading"
      />
      <button @click.prevent="send"
              class="btn btn-primary"
              :disabled="send_loading"
      >
          отправить
      </button>
    </div>
  </div>
</template>

<script>
import { sendMessage } from '@/services/chat_service.js';
import {mapGetters, mapMutations} from "vuex";

export default {
    name: "SendMessage",
    props: {
        chat_id: {
            type: Number,
            required: true,
        }
    },
    data(){
        return {
            message: '',
            send_loading: false
        }
    },
    computed: {
        ...mapGetters('storeChat', ['load_send']),
    },
    methods: {
        ...mapMutations('storeChat', ['pushMessages', 'setAddMessagesType']),
        async send(){
            this.send_loading = true;
            if(this.message.length < 2){
                return true;
            }
            const messageData = { message: this.message, chat_room_id: this.chat_id };
            const resSendMessage = await sendMessage(messageData);
            this.message = '';
            this.send_loading = false;
            //this.pushMessages(resSendMessage);
        }
    }
}
</script>
