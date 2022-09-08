<template>
    <a href="#" class="list-group-item list-group-item-action border-0" :class="{ 'creator_chat' : is_chat_creator }">
        <div class="d-flex align-items-start">
            <FontAwesomeIcon v-if="is_chat_creator" icon="crown" />
            <img :src="user.avatar_src"
                 class="user-item-img me-4"
                 :alt="user.name"
                 width="40"
                 height="40"
            >
            <div class="flex-grow-1 ml-3">
                {{ user.name }}
                <div class="small"><span class="fas fa-circle chat-online"></span> Online</div>
            </div>
        </div>
    </a>
</template>

<script>


import { library } from '@fortawesome/fontawesome-svg-core';
import { faCrown } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faCrown);

export default {
  props: {
      user: {
          type: Object,
          required: true,
      },
      is_chat_creator: {
          type: Boolean,
          default: false,
      }
  },
    components: {
        FontAwesomeIcon
    }
}
</script>

<style scoped>
    .creator_chat{
        order: -1;
    }

    .creator_chat svg{
        color: #fcd975;
        position: absolute;
        top: 50%;
        right: 0;
        transform: translate(0, -50%);
    }
</style>
