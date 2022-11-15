import { setUserDataToLocalStorage, userData } from '../helpers/helpers';

const storeUser = {
    namespaced: true,
    state: () => {
        return {
            id: userData()?.id,
            name: userData()?.name,
            avatar: userData()?.avatar,
            avatar_src: userData()?.avatar_src,
            auth_remember: !!localStorage.getItem('remember'),
        }
    },
    actions: {

    },
    mutations: {
        setUser(state, user){
            state.id = user?.id;
            state.name = user?.name;
            state.avatar = user?.avatar;
            state.avatar_src = user?.avatar_src;
            // Обновляем данные пользователя в localStorage
            setUserDataToLocalStorage(state);
        },
        setAuthRemember(state, remember){
            state.auth_remember = remember;
        }
    },
    getters: {
        id: state => state.id,
        name: state => state.name,
        avatar: state => state.avatar,
        avatar_src: state => state.avatar_src,
        auth_remember: state => state.auth_remember,
    }
};

export default storeUser;
