<template>
    <main class="content">
        <div class="container p-0">
            <div v-if="!is_socket_connect" class="is-socket-connect-container">
                Отсутствует соединение с сервером
            </div>
            <Header/>
            <router-view />
        </div>
        <div v-if="is_site_not_work" class="site-not-work-container">
            <div>Чат временно не работает</div>
        </div>
    </main>
</template>

<script>
import {mapGetters, mapMutations} from "vuex";

import Header from "../../components/Header";
import {loadChatListPage} from "../../services/chat_service";

export default {
    name: "DefaultLayout",
    components: { Header },
    mounted() {
        this.loadChats();
    },
    unmounted() {
        this.$store.state.socket.disconnect();
    },
    computed: {
        ...mapGetters(['socket', 'is_site_not_work', 'is_socket_connect']),
        ...mapGetters('storeChatsList', ['chats']),
        ...mapGetters({ current_user_id: 'storeUser/id' }),
    },
    methods: {
        ...mapMutations('storeChatsList', ['setChats']),
        ...mapMutations(['setIsSiteNotWork']),
        async loadChats(){
            try {
                const resChatsList = await loadChatListPage();
                const chatsList = resChatsList.chats;

                this.setChats(chatsList);

                this.socket.io.opts.query = { user_id: this.current_user_id};
                this.socket.connect();
            }catch(e){
                this.setIsSiteNotWork(true);
                console.log('error load chats');
            }
        },
    }
}
</script>

<style scoped>
.site-not-work-container{
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    font-size: 32px;
    background-color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
}

.site-not-work-container > div{
    text-align: center;
}

.is-socket-connect-container{
    text-align: center;
    padding: 6px 0;
    background-color: #ff4848;
    color: white;
}
</style>
