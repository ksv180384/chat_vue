<template>
    <div>
        <component :is="layout">
            <router-view :layout="layout"/>
        </component>
        <LoadPage :load="load_page"/>
        <MessageNotification/>
    </div>
</template>

<script>

import {mapGetters} from "vuex";

import LoadPage from './components/LoadPage';
import MessageNotification from "./components/MessageNotification";

export default {
    components: {
        MessageNotification,
        LoadPage,
    },
    computed: {
        ...mapGetters(['load_page']),
        layout(){
            //return this.$route.meta.layout || 'DefaultLayout'
            return this.$route.meta.layout;
        }
    }
}
</script>

