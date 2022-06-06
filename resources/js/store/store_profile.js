import { setUserData, userData } from '../helpers/helpers';

const storeProfile = {
    namespaced: true,
    state: {
        name: userData().name,
        avatar: userData().avatar,
        avatar_src: userData().avatar_src,

    },
    actions: {

    },
    mutations: {
        setProfile(state, profile) {
            setUserData(profile);
            state.name = profile.name;
            state.avatar = profile.avatar;
            state.avatar_src = profile.avatar_src;
        },
        setName(state, name){
            state.name = name;
        },
        setAvatarSrc(state, avatar_src){
            state.avatar_src = avatar_src;
        },
        setAvatar(state, avatar){
            state.avatar = avatar;
        }
    },
    getters: {
        name: state => {
            return state.name;
        },
        avatar_src: state => {
            return state.avatar_src;
        },
        avatar: state => {
            return state.avatar;
        },
    }
}

export default storeProfile;
