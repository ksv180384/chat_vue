<template>
    <div v-if="load"
         class="load-page-container"
    >
        Загрузка
    </div>
</template>

<script setup>

const props = defineProps({
  load: { type: Boolean, default: false }
});
</script>

<style scoped>
    .load-page-container{
        position: fixed;
        top:0;
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #ccc;
        z-index: 999;
    }
</style>
