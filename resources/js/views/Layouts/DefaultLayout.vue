<template>
    <main class="content">
        <div class="container p-0">
            <Header/>
            <router-view />
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
        ...mapGetters('storeChatsList', ['chats']),
        ...mapGetters({ current_user_id: 'storeUser/id' }),
    },
    methods: {
        ...mapMutations('storeChatsList', ['setChats']),
        async loadChats(){
            try {
                const resChatsList = await loadChatListPage();
                const chatsList = resChatsList.chats;

                this.$store.state.socket.io.opts.query = { user_id: this.current_user_id};
                this.$store.state.socket.connect();

                this.setChats(chatsList);
            }catch(e){
                console.log('error load chats');
            }
        },
    }
}
</script>
