<template>
    <div class="chat-users-list">
        <template v-if="chat_users">
            <UserItem v-for="user in chat_users" :key="user.id" :user="user"/>
        </template>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

import UserItem from "../components/UserItem";

export default {
    name: "ChatUsersList",
    props: ['chat_users'],
    components: {
        UserItem
    },
}
</script>

<style scoped>

</style>
