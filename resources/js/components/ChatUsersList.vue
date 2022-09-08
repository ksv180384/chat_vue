<template>
    <div class="chat-users-list">
        <template v-if="chat_users">
            <UserItem v-for="user in chat_users"
                      :key="user.id"
                      :user="user"
                      :is_chat_creator="chat_creator_id === user.id"/>
        </template>
    </div>
</template>

<script>

import UserItem from "../components/UserItem";

export default {
    name: "ChatUsersList",
    props: {
        chat_users: {
            type: Array,
            default: []
        },
        chat_creator_id: {
            type: Number,
            required: true,
        }
    },
    components: {
        UserItem
    },
}
</script>

<style scoped>
    .chat-users-list{
        height: calc(100vh - 230px);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }
</style>
