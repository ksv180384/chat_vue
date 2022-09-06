<template>
    <div class="px-4 d-none d-md-block">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <input @input="handleInput"
                       :value="modelValue"
                       type="text"
                       class="form-control my-3"
                       placeholder="Поиск..."
                />
            </div>
            <div class="ms-2">
                <AddUserChat/>
            </div>
        </div>
    </div>
</template>

<script>
import AddUserChat from "./AddUserChat";
export default {
    name: "SearchUserChat",
    components: { AddUserChat },
    props: ['modelValue'],
    methods: {
        handleInput(e){
            this.$emit('update:modelValue', e.target.value);
        }
    }
}
</script>
