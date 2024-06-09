import { defineStore } from 'pinia';

const initNotifications = [];

export const useMessageNotificationsStore = defineStore('messageNotificationsStore', {
  state: () => ({
    notifications: initNotifications,
  }),
  actions: {
    pushNotification(state, notification){
      this.notifications = [...this.notifications, notification];
    },
    popNotification(state, id){
      this.notifications = this.notifications.filter(item => item.id !== id)
    }
  }
});
