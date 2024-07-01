<template>
  <button
    type="button"
    class="btn"
    :class="[classType, classSize]"
    data-bs-toggle="modal"
    :data-bs-target="`#${modalTargetId}`"
  >
    <slot></slot>
  </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  modalTargetId: { type: String, required: true },
  type: { type: String, default: 'secondary' },
  size: { type: String, default: 'xl' },
});

const classType = computed(() => `btn-${props.type}`);
const classSize = computed(() => `btn-${props.size}`);
</script>

<style scoped>

</style>
