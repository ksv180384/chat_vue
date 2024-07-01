<template>
  <a
    href="#"
    class="list-group-item list-group-item-action border-0"
    :class="{ active: active }"
    @click.prevent="setActive"
  >
    <div class="d-flex align-items-start">
      <img :src="user.avatar_src"
           class="user-item-img me-2"
           :alt="user.name"
           width="40"
           height="40"
      >
      <div class="flex-grow-1 ml-3">
        {{ user.name }}
      </div>
    </div>
  </a>
</template>

<script setup>

const props = defineProps({
  user: { type: Object, required: true },
  active: { type: Boolean, required: false },
});
const emits = defineEmits(['on-change-active']);

const setActive = () => {
  emits('on-change-active', props.user.id);
}
</script>

<style scoped>

</style>
