const storeProfile = {
    state: {
        name: '',
        avatar: '',
        avatar_src: '',
    },
    actions: {

    },
    mutations: {
        setProfile(state, profile) {
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
        profile: state => {
            return state.profile;
        },
    }
}

export default storeProfile;
