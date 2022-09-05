<template>
    <div class="error-container">
        <div class="error-content">
            <h1>Ошибка 404</h1>
            Такой страницы не существует. <router-link to="/">На главную</router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: "PageNotFound"
}
</script>

<style scoped>
.error-container{
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100vh;
}

.error-content{
    display: inline-block;
    text-align: center;
}
</style>
