<template>
    <div class="modal fade"
         :id="id"
         tabindex="-1"
         :aria-labelledby="id"
         aria-hidden="true"
         :ref="id"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" v-if="title">
                    <h5 class="modal-title" :id="id + 'lLabel'">
                        <slot name="title">Modal title</slot>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <slot></slot>
                </div>
                <div class="modal-footer" v-if="footer">
                    <slot name="footer">
                        <button type="button"
                                class="btn btn-primary"
                                v-if="show_ok"
                        >Сохранить</button>
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                                v-if="show_close"
                        >Отмена</button>
                    </slot>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

export default {
    name: 'WModal',
    props: {
        id: {
            required: true,
        },
        footer: {
            default: true,
        },
        title: {
            default: true,
        },
        show_ok: {
            default: true,
        },
        show_close: {
            default: true,
        },
    },
    mounted() {
        //console.log(this.$refs[this.id]);
    }
}
</script>

<style scoped>

</style>
