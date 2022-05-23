<template>
    <div class="chat-users-list">
        <template v-if="chatUsers">
            <UserItem v-for="user in chatUsers" :key="user.id" :user="user"/>
        </template>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

import UserItem from "../components/UserItem";

export default {
    name: "ChatUsersList",
    components: {
        UserItem
    },
    computed: {
        ...mapGetters([
            'chatUsers',
        ])
    },
}
</script>

<style scoped>

</style>
