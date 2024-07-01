import { defineStore } from 'pinia';

const initChats = [];

export const useRoomsStore = defineStore('chatsRoomsStore', {
  state: () => ({
    chats: initChats,
  }),
  actions: {
    setChats(chats){
      this.chats = chats;
    },
    pushChat(chat){
      this.chats.push(chat);
    },
    removeChat(chatId){
      this.chats = this.chats.filter(item => item.id !== chatId);
    },
    clear(){
      this.chats = initChats;
    },
    changeChatSettingsById(chatId, settings){
      this.chats = this.chats.map(item => {
        if(item.id === chatId){
          item.settings = settings;
        }
        return item;
      });
    },
  }
});
