<template>
  <div class="chat-message-right pb-4">
    <div class="ms-2">
      <img :src="message.user.avatar_src"
           class="user-item-img mr-1"
           :alt="message.user.name"
           width="40"
           height="40"
      />
      <div class="text-muted small text-nowrap mt-2">{{ message.created_at_time }}</div>
    </div>
    <div class="flex-shrink-1 rounded py-2 px-3 mr-3 bg-im-message">
      <div class="font-weight-bold mb-1">Я</div>
      {{ message.message }}
    </div>
  </div>
</template>

<script>
export default {
  name: "ChatMessageItem",
  props: {
    message: {
      message: String,
      created_at: String,
      user: {
        name: String,
        avatar: String,
      }
    }
  }
}
</script>

<style scoped>
    .bg-im-message{
        background-color: #dfeafb;
    }
</style>
