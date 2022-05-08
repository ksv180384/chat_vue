<template>
  <div class="flex-grow-0 py-3 px-4 border-top">
    <div class="input-group">
      <input type="text"
             class="form-control"
             placeholder="Введите сообщение"
             v-model="message"
      />
      <button @click.prevent="send" class="btn btn-primary">отправить</button>
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
        }
    },
    methods: {
        send(){
            if(this.message.length < 2){
                return true;
            }
            api.post('/chat/send-message/', { message: this.message, chat_room_id: this.chat_id })
                .then(res => {
                    //this.users = res.users;
                    //this.loading = false;
                    //this.$store.commit('addChat', res.chat)
                }).catch(error => {
                // handle error
                this.loading = false;
                this.error = error.response.data.message;
            });
        }
    }
}
</script>
