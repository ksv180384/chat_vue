<template>
    <h1 class="h3 my-3">
        <span class="me-3">
            <router-link to="/"><FontAwesomeIcon icon="caret-left" /></router-link>
        </span>
        <span v-if="chat">{{ chat.title }}</span>
    </h1>
    <div class="card">
        <div class="row g-0">
            <div class="col-12 col-lg-3 col-xl-3 border-right">
                <SearchUserChat/>
                <div class="chat-users-list">
                    <template v-if="users">
                        <UserItem v-for="user in users" :key="user.id" :user="user"/>
                    </template>
                </div>
            </div>
            <div class="col-12 col-lg-9 col-xl-9">
                <div class="position-relative">
                    <div class="chat-messages p-4" ref="messages_container">
                        <div v-if="messages">
                            <div v-for="message in messages" :key="message.id">
                                <ChatMessage v-if="message.user.id === user.id" :message="message"/>
                                <ChatMessageLeft v-else :message="message"/>
                            </div>
                        </div>
                    </div>
                </div>
                <SendMessage/>
            </div>
        </div>
    </div>
</template>

<script>

import ChatMessage from "../components/ChatMessage";
import ChatMessageLeft from "../components/ChatMessageLeft";
import UserItem from "../components/UserItem";
import SearchUserChat from "../components/SearchUserChat";
import SendMessage from "../components/SendMessage";

import { library } from '@fortawesome/fontawesome-svg-core';
import { faCaretLeft } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faCaretLeft)

export default {
  name: "chat",
  components: {
    SendMessage,
    SearchUserChat,
    UserItem,
    ChatMessageLeft,
    ChatMessage,
    FontAwesomeIcon
  },
  data(){
    return {
      user: this.$store.getters.user
    }
  },
  computed: {
    chat(){
      return this.$store.getters.chat;
    },
    users(){
      return this.$store.getters.chatUsers;
    },
    messages(){
      return this.$store.getters.messages;
    }
  },
  methods: {
    scrollToBottom(smooth) {
      const container = this.$refs.messages_container;
      if (smooth){
        container.scrollTo({
          top: container.scrollHeight,
          behavior: 'smooth'
        });
      }else{
        container.scrollTop = container.scrollHeight;
      }

    }
  },
  mounted() {
    this.$store.dispatch('loadChat', this.$route.params.id);
  },
  updated() {
    this.scrollToBottom();
  }
}
</script>
