<template>
  <component :is="this.$route.meta.layout || 'DefaultLayout'">
    <router-view :layout="layout" />
  </component>
</template>

<script>

export default {
  components: {}
}
</script>

