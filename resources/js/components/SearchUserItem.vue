<template>
    <a href="#" class="list-group-item list-group-item-action border-0"
        :class="{ active: active }"
       @click="setActive"
    >
        <div class="d-flex align-items-start">
            <img :src="user.avatar_src"
                 class="user-item-img me-2"
                 :alt="user.name"
                 width="40"
                 height="40"
            >
            <div class="flex-grow-1 ml-3">
                {{ user.name }}
            </div>
        </div>
    </a>
</template>

<script>
export default {
    name: "SearchUserItem",
    props: {
        user: {
            type: Object,
            required: true
        },
        active: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        setActive(){
            this.$emit('on-change-active', this.user.id);
        }
    }
}
</script>

<style scoped>

</style>
