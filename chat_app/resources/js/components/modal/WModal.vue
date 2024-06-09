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
        <div class="modal-header">
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
        <div class="modal-footer">
          <slot name="footer"/>
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
