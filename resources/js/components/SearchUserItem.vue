<template>
    <a href="#" class="list-group-item list-group-item-action border-0"
        :class="{ active: active }"
       @click="setActive"
    >
        <div class="d-flex align-items-start">
            <img src="https://bootdey.com/img/Content/avatar/avatar5.png"
                 class="rounded-circle me-4"
                 alt="{{ user.name }}"
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
    data(){
        return {}
    },
    methods: {
        setActive(){
            this.$emit('change-active', this.user.id);
        }
    }
}
</script>

<style scoped>

</style>
