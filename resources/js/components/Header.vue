<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <img class="avatar-img me-2" :src="avatar_src" />
                <router-link class="navbar-brand" to="/profile">
                    {{ name }}
                </router-link>
            </div>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                </ul>
                <form @submit.prevent="logout" class="d-flex">
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
        logout(){
            api.post('logout')
                .then(() => {
                    localStorage.removeItem('user_token');
                    localStorage.removeItem('user');
                    api.defaults.headers.common['Authorization'] = null;
                    this.setUser(null);
                    this.$router.push('/login');
                })
                .catch(() => {

                });
        }
    }
}
</script>

<style scoped>

</style>
