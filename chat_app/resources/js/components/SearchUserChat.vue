<template>
  <div class="px-4 d-none d-md-block">
    <div class="d-flex align-items-center">
      <div class="flex-grow-1">
        <input
          @input="handleInput"
          :value="modelValue"
          type="text"
          class="form-control my-3"
          placeholder="Поиск..."
        />
      </div>
      <div v-if="showBtnJoinUser" class="ms-2">
        <AddUserChat/>
      </div>
    </div>
  </div>
</template>

<script setup>
import AddUserChat from '@/components/AddUserChat.vue';

const props = defineProps({
  modelValue: { type: String, default: '' },
  showBtnJoinUser: { type: Boolean, default: false },
});
const emits = defineEmits(['update:modelValue']);

const handleInput = (e) => {
  emits('update:modelValue', e.target.value);
}

/*
export default {
    name: "SearchUserChat",
    components: { AddUserChat },
    props: {
        modelValue: {
            type: String,
        },
        show_btn_join_user: {
            type: Boolean,
            default: false,
        }
    },
    methods: {
        handleInput(e){
            this.$emit('update:modelValue', e.target.value);
        }
    }
}
*/
</script>
