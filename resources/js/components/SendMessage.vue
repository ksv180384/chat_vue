<template>
  <div class="flex-grow-0 py-3 px-4 border-top">
    <div class="input-group">
      <input @keyup.enter="send"
             type="text"
             class="form-control"
             placeholder="Введите сообщение"
             v-model="message"
      />
      <button @click.prevent="send"
              class="btn btn-primary"
              :disabled="load_send"
      >
          отправить
      </button>
    </div>
  </div>
</template>

<script>
import { sendMessage } from '../services/chat_service';
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
        }
    },
    computed: {
        ...mapGetters('storeChat', ['load_send']),
    },
    methods: {
        ...mapMutations('storeChat', ['pushMessages', 'setAddMessagesType']),
        async send(){
            if(this.message.length < 2 && !this.send_loading){
                return true;
            }
            const messageData = { message: this.message, chat_room_id: this.chat_id };
            const resSendMessage = await sendMessage(messageData);
            this.message = '';
            this.pushMessages(resSendMessage);
        }
    }
}
</script>
