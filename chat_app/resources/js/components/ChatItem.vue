<template>
    <router-link :to="{ path: `/chat/${chat.id}` }"
                 class="list-group-item list-group-item-action border-0"
    >
        <div class="d-flex align-items-start">
            {{ chat.title }}
        </div>
    </router-link>
</template>

<script>
export default {
    name: 'ChatItem',
    props: {
        chat:{
            type: Object,
            required: true,
        }
    }
}
</script>
