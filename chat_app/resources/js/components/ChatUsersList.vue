<template>
  <div class="chat-users-list">
    <template v-if="chatUsers">
      <UserItem
        v-for="user in chatUsers"
        :key="user.id"
        :user="user"
        :is_chat_creator="chatCreatorId === user.id"/>
    </template>
  </div>
</template>

<script setup>
import UserItem from '@/components/UserItem.vue';

const props = defineProps({
  chatUsers: { type: Array, default: [] },
  chatCreatorId: { type: Number, required: true },
});
</script>

<style scoped>
.chat-users-list{
  height: calc(100vh - 230px);
  overflow-y: auto;
  display: flex;
  flex-direction: column;
}
</style>
