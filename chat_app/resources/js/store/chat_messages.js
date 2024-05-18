import { defineStore } from 'pinia';

const initMessages = [];

export const useChatMessagesStore = defineStore('chatMessagesStore', {
  state: () => ({
    messages: initMessages,
    chat_id: null,
    page: 1,
    isNextPage: false,
    is_loading_messages: false,
  }),
  actions: {
    set(messages){
      this.messages = messages;
    },
    push(message){
      if(Array.isArray(message)){
        this.messages = [...message, ...this.messages];
      }else{
        this.messages = [...this.messages, message];
      }
    },
    remove(id){
      this.messages = this.messages.filter(item => item.id !== id)
    },
    incrementPage(){
      this.page += 1;
    }
  }
});
