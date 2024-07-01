<template>
  <div class="chat-message-left pb-4">
    <div class="me-2">
        <img :src="message.user.avatar_src"
             class="user-item-img mr-1"
             :alt="message.user.name"
             width="40"
             height="40"
        />
        <div class="text-muted small text-nowrap mt-2">{{ message.created_at_time }}</div>
    </div>
    <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
      <div class="font-weight-bold mb-1">{{ message.created_at }} {{ message.user.name }}</div>
      {{ message.message }}
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  message: { type: Object, default: {} },
});
</script>
