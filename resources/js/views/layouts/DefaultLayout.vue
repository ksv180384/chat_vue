<template>
  <main class="content">
    <div class="container p-0">
      <Header/>
      <router-view />
    </div>
  </main>
</template>

<script>
import Header from "../../components/Header";
export default {
  name: "DefaultLayout",
  components: {Header}
}
</script>

<style scoped>

</style>