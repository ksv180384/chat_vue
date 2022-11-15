<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="d-flex align-items-center w-100">
            <div class="d-flex align-items-center">
                <img class="avatar-img me-2" :src="avatar_src" />
                <router-link class="navbar-brand" to="/profile">
                    {{ name }}
                </router-link>
            </div>

            <div class="flex-grow-1 text-end pe-2" id="navbarSupportedContent">
                <form @submit.prevent="logout">
                    <button class="btn btn-outline-success" type="submit">
                        Выход
                    </button>
                </form>
            </div>
        </div>
    </nav>
</template>

<script>

import api from '../helpers/api';
import {mapGetters, mapMutations} from "vuex";

export default {
    computed: {
        ...mapGetters('storeUser', [
            'name',
            'avatar',
            'avatar_src',
        ])
    },
    methods: {
        ...mapMutations('storeUser', ['setUser']),
        async logout(){

            const resLogout = await api.post('logout');
            localStorage.removeItem('user_token');
            localStorage.removeItem('user');
            localStorage.removeItem('remember');
            api.defaults.headers.common['Authorization'] = null;
            this.setUser(null);
            this.$router.push('/login');
        }
    }
}
</script>

<style scoped>

</style>
