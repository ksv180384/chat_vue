const storeMessageNotifications = {
    namespaced: true,
    state: () => {
        return {
            notifications: [],
        }
    },
    mutations: {
        pushNotification(state, notification){
            state.notifications = [...state.notifications, notification];
        },
        popNotification(state, id){
            state.notifications = state.notifications.filter(item => item.id !== id)
        }

    },
    getters: {
        notifications: state => state.notifications,
    },
};

export default storeMessageNotifications;
