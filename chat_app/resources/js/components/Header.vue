<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="d-flex align-items-center w-100">
      <div class="d-flex align-items-center">
        <img
          class="avatar-img me-2"
          :src="user.avatar"
          :alt="user.name"
        />
        <router-link class="navbar-brand" :to="{ name: 'profile' }">
          {{ user.name }}
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
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthUserStore } from '@/store/auth_user.js';
import { useSocketStore } from '@/store/socket.js';
import api from '@/services/api.js';

const router = useRouter();
const authUserStore = useAuthUserStore();
const socketStore = useSocketStore();
const user = computed(() => authUserStore.auth_data || {});

const logout = async () => {

  const resLogout = await api.post('logout');
  authUserStore.clearUser();
  socketStore.socket.disconnect();
  // localStorage.removeItem('user_token');
  // localStorage.removeItem('user');
  // localStorage.removeItem('remember');
  // api.defaults.headers.common['Authorization'] = null;
  router.push({ name: 'login' });
}
</script>

<style scoped>

</style>
