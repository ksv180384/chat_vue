<template>
    <button type="button"
            :class="initClasses"
            data-bs-toggle="modal"
            :data-bs-target="`#${modal}`"
    >
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: 'BtnModal',
    props: {
        modal: {
            type: String,
            required: true,
        },
        variant: {
            type: String,
            default: 'secondary'
        },
        size: {
            type: String,
            default: 'xl'
        }
    },
    computed: {
        initClasses(){
            let classes = 'btn';
            classes += ' btn-' + this.variant;
            classes += ' btn-' + this.size;
            return classes;
        }
    }
}
</script>

<style scoped>

</style>
