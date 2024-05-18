<template>
  <router-link
    :to="{ name: 'chat', params: { id: chat.id } }"
    class="list-group-item list-group-item-action border-0"
  >
    <div class="d-flex align-items-start">
      {{ chat.title }}
    </div>
  </router-link>
</template>

<script setup>
const props = defineProps({
  chat: { type: Object, required: true }
});
</script>
