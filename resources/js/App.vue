<template>
    <div>
        <component :is="this.$route.meta.layout || 'DefaultLayout'">
            <router-view :layout="layout" />
        </component>
    </div>
</template>

<script>

export default {
    components: {}
}
</script>

