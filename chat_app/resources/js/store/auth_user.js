import { defineStore } from 'pinia';
const initAuthUser = null;

export const useAuthUserStore = defineStore('authUserStore', {
  state: () => ({
    auth_data: initAuthUser,
  }),
  actions: {
    setUser(user){
      this.auth_data = { ...this.auth_data, ...user };
    },
    clearUser(){
      this.auth_data = initAuthUser;
    },
  }
});
