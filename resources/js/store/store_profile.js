const storeProfile = {
    state: {
        name: '',
    },
    actions: {

    },
    mutations: {
        setProfile(state, profile) {
            state.name = profile.name;
        },
        setName(state, name){
            state.name = name;
        }
    },
    getters: {
        profile: state => {
            return state.profile;
        },
    }
}

export default storeProfile;
