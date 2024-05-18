import { defineStore } from 'pinia';
import { setUserDataToLocalStorage, userData } from '@/helpers/helpers.js';

const initAuthUser = {
  id: userData()?.id,
  name: userData()?.name,
  avatar: userData()?.avatar,
  avatar_src: userData()?.avatar_src,
  auth_remember: !!localStorage.getItem('remember'),
};

export const useAuthUserStore = defineStore('authUserStore', {
  state: () => ({
    auth_user: initAuthUser,
  }),
  actions: {
    setUser(state, user){
      this.auth_user.id = user?.id;
      this.auth_user.name = user?.name;
      this.auth_user.avatar = user?.avatar;
      this.auth_user.avatar_src = user?.avatar_src;
      // Обновляем данные пользователя в localStorage
      setUserDataToLocalStorage(this.auth_user);
    },
    setAuthRemember(remember){
      this.auth_user.auth_remember = remember;
    }
  }
});
