<template>
  <a href="#"
     class="user-item list-group-item list-group-item-action border-0"
     :class="{ 'creator-chat' : isChatCreator, 'user-online' : isOnline }"
  >
    <div class="d-flex align-items-start">
      <div class="text-center me-2">
        <img
          :src="user.avatar_src"
           class="user-item-img"
           :alt="user.name"
           width="40"
           height="40"
        >
      </div>
      <div class="flex-grow-1 ml-3">
        <div>
          <FontAwesomeIcon v-if="isChatCreator" icon="crown" />
          {{ user.name }}
        </div>
        <div class="small">
          <span v-if="isOnline" class="user-status online">Online</span>
          <span v-else class="user-status offline">Offline</span>
        </div>
      </div>
    </div>
  </a>
</template>

<script setup>
import { computed } from 'vue';
import { useUsersOnlineStore } from '@/store/users_online.js';

import { library } from '@fortawesome/fontawesome-svg-core';
import { faCrown } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faCrown);

const props = defineProps({
  user: { type: Object, required: true },
  isChatCreator: { type: Boolean, default: false },
});

const usersOnlineStore = useUsersOnlineStore();

const isOnline = computed(() => {
  console.log(props.user.id);
  console.log(usersOnlineStore.ids);
  return usersOnlineStore.isUserOnline(props.user.id);
});
</script>

<style scoped>
.user-item.creator-chat{
  order: -1 !important;
}

.creator-chat svg{
  color: #fcd975;
  font-size: 10px;
}

.user-status{
  position: relative;
  padding-left: 18px;
}

.user-status:after{
  content: '';
  display: block;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  position: absolute;
  left: 2px;
  top: 50%;
  transform: translate(0, -50%);
}

.user-item{
  order: 1;
}

.user-item.user-online{
  order: 0;
}

.user-status.online:after{
  background-color: green;
}

.user-status.offline:after{
  background-color: red;
}
</style>
