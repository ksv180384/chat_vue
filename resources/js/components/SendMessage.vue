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
      >отправить</button>
    </div>
  </div>
</template>

<script>
import api from "../helpers/api";
import {mapMutations} from "vuex";

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
            load_send: false,
        }
    },
    methods: {
        ...mapMutations('storeChat', ['pushMessages', 'setAddMessagesType']),
        send(){
            if(this.message.length < 2 && !this.send_loading){
                return true;
            }
            this.load_send = true;
            api.post('/chat/messages/send', { message: this.message, chat_room_id: this.chat_id })
                .then(res => {
                    this.load_send = false;
                    this.message = '';

                    this.setAddMessagesType('send');
                    this.pushMessages(res);

                    this.$store.state.socket.emit(
                        'message',
                        {room: `chat_${this.chat_id}`, message: res}
                    );
                }).catch(error => {
                    // handle error
                    this.load_send = false;
                    this.error = error.response.data.message;
                });
        }
    }
}
</script>
