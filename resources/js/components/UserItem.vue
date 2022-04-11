<template>
    <a href="#" class="list-group-item list-group-item-action border-0">
        <div class="d-flex align-items-start">
            <img src="https://bootdey.com/img/Content/avatar/avatar5.png"
                 class="rounded-circle me-4"
                 alt="{{ user.name }}"
                 width="40"
                 height="40"
            >
            <div class="flex-grow-1 ml-3">
                {{ user.name }}
                <div class="small"><span class="fas fa-circle chat-online"></span> Online</div>
            </div>
        </div>
    </a>
</template>

<script>
export default {
  props: ['user'],
}
</script>
