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
          <h5 class="modal-title" :id="`${id}_label`">
            <slot name="title">Modal title</slot>
          </h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <slot></slot>
        </div>
        <div
          v-if="footer"
          class="modal-footer"
        >
          <slot name="footer">
            <button
              v-if="isShowBtnConfirm"
              type="button"
              class="btn btn-primary"
            >
              Сохранить
            </button>
            <button
              v-if="isShowBtmClose"
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Отмена
            </button>
          </slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  id: { type: String, required: true },
  footer: { type: String, default: '' },
  title: { type: String, default: '' },
  isShowBtnConfirm: { type: Boolean, default: true },
  isShowBtmClose: { type: Boolean, default: true },
});
</script>

<style scoped>

</style>
