<template>
    <div>
        <component :is="this.$route.meta.layout || 'DefaultLayout'">
            <router-view :layout="layout" />
        </component>
        <LoadPage :load="load_page"/>
    </div>
</template>

<script>

import {mapGetters} from "vuex";

import LoadPage from './components/LoadPage';

export default {
    components: {
        LoadPage
    },
    computed: {
        ...mapGetters(['load_page']),
    }
}
</script>

