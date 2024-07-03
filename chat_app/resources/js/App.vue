<template>
  <div>
    <component :is="layout">
      <router-view :layout="layout"/>
    </component>
    <LoadPage :load="loadPage"/>
    <MessageNotification/>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { usePageStore } from '@/store/page.js';

import LoadPage from '@/components/LoadPage.vue';
import MessageNotification from '@/components/MessageNotification.vue';

const pageStore = usePageStore();
const route = useRoute();

const layout = computed(() => route.meta.layout);
const loadPage = computed(() => pageStore.isLoading);

</script>

