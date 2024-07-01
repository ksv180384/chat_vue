import { defineStore } from 'pinia';

const initIds = [];

export const useUsersOnlineStore = defineStore('usersOnlineStore', {
  state: () => ({
    ids: initIds,
  }),
  getters: {
    isUserOnline(state){
      return (userId) => state.ids.includes(userId);
    }
  },
  actions: {
    setUsersIds(ids){
      this.ids = ids;
    },
    addId(id){
      this.ids = [...this.ids, id];
    },
    clearData(){
      this.ids = initIds;
    },
  }
});
