import { userData } from '../helpers/helpers';

const storeUser = {
    namespaced: true,
    state: () => {
        return {
            id: userData().id,
            name: userData().name,
            avatar: userData().avatar,
            avatar_src: userData().avatar_src,
        }
    },
    actions: {

    },
    mutations: {
        setUser(state, user){
            state.id = user.id;
            state.name = user.name;
            state.avatar = user.avatar;
            state.avatar_src = user.avatar_src;
        }
    },
    getters: {
        id: state => state.id,
        name: state => state.name,
        avatar: state => state.avatar,
        avatar_src: state => state.avatar_src,
    }
};

export default storeUser;
