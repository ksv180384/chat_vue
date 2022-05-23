<template>
  <div class="chat-message-right pb-4">
    <div>
      <img :src="message.user.avatar"
           class="rounded-circle mr-1"
           :alt="message.user.name"
           width="40"
           height="40"
      />
      <div class="text-muted small text-nowrap mt-2">{{ message.created_at_humans }}</div>
    </div>
    <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
      <div class="font-weight-bold mb-1">You</div>
      {{ message.message }}
    </div>
  </div>
</template>

<script>
export default {
  name: "ChatMessage",
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
