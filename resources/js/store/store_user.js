import { userData } from '../helpers/helpers';

const storeUser = {
    namespaced: true,
    state: () => {
        return {
            user: userData(),
        }
    },
    actions: {

    },
    mutations: {
        setUser(state, user){
            state.user = user;
        }
    },
    getters: {
        user: state => state.user,
    }
};

export default storeUser;
