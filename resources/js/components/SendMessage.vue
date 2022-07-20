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
              :disabled="send_loading"
      >отправить</button>
    </div>
  </div>
</template>

<script>
import api from "../helpers/api";

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
            send_loading: false,
        }
    },
    methods: {
        send(){
            if(this.message.length < 2 && !this.send_loading){
                return true;
            }
            this.send_loading = true;
            api.post('/chat/messages/send', { message: this.message, chat_room_id: this.chat_id })
                .then(res => {
                    this.send_loading = false;
                    this.message = '';

                    this.$emit('onSendMessage', res);

                    this.$store.state.socket.emit(
                        'message',
                        {room: `chat_${this.chat_id}`, message: res}
                    );
                }).catch(error => {
                // handle error
                this.send_loading = false;
                this.error = error.response.data.message;
            });
        }
    }
}
</script>
