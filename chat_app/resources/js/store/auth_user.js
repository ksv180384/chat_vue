import { defineStore } from 'pinia';
import { setUserDataToLocalStorage, userData } from '@/helpers/helpers.js';

// const initAuthUser = {
//   id: userData()?.id,
//   name: userData()?.name,
//   avatar: userData()?.avatar,
//   avatar_src: userData()?.avatar_src,
//   auth_remember: !!localStorage.getItem('remember'),
// };

export const useAuthUserStore = defineStore('authUserStore', {
  state: () => ({
    auth_user: {
      id: userData()?.id,
      name: userData()?.name,
      avatar_src: userData()?.avatar_src,
      auth_remember: !!localStorage.getItem('remember'),
    },
  }),
  actions: {
    setUser(user){
      this.auth_user = user;
      // Обновляем данные пользователя в localStorage
      setUserDataToLocalStorage(this.auth_user);
    },
    setAuthRemember(remember){
      this.auth_user.auth_remember = remember;
    }
  }
});
