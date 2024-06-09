<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="d-flex align-items-center w-100">
      <div class="d-flex align-items-center">
        <img
          class="avatar-img me-2"
          :src="authUserStore.auth_data.avatar"
          :alt="authUserStore.auth_data.name"
        />
        <router-link class="navbar-brand" :to="{ name: 'profile' }">
          {{ authUserStore.auth_data.name }}
        </router-link>
      </div>

      <div class="flex-grow-1 text-end pe-2">
        <form @submit.prevent="logout">
          <button
            class="btn btn-outline-success"
            type="submit"
          >
            Выход
          </button>
        </form>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useAuthUserStore } from '@/store/auth_user.js';
import api from '@/helpers/api.js';

const router = useRouter();
const authUserStore = useAuthUserStore();

const logout = async () => {

  const resLogout = await api.post('logout');
  authUserStore.setUser(null);
  // localStorage.removeItem('user_token');
  // localStorage.removeItem('user');
  // localStorage.removeItem('remember');
  // api.defaults.headers.common['Authorization'] = null;
  router.push({ name: 'login' });
}
</script>

<style scoped>

</style>
