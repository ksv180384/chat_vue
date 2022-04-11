<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>

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

export default {
    methods: {
        logout(){
            api.post('logout')
                .then(() => {
                    localStorage.removeItem('user_token');
                    localStorage.removeItem('user');
                    api.defaults.headers.common['Authorization'] = null;
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
