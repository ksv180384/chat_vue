<template>
    <a href="#"
       class="user-item list-group-item list-group-item-action border-0"
       :class="{ 'creator-chat' : is_chat_creator, 'user-online' : isOnline }"
    >
        <div class="d-flex align-items-start">
            <div class="text-center me-2">
                <img :src="user.avatar_src"
                     class="user-item-img"
                     :alt="user.name"
                     width="40"
                     height="40"
                >
            </div>
            <div class="flex-grow-1 ml-3">
                <div>
                    <FontAwesomeIcon v-if="is_chat_creator" icon="crown" />
                    {{ user.name }}
                </div>
                <div class="small">
                    <span v-if="isOnline" class="user-status online">Online</span>
                    <span v-else class="user-status offline">Offline</span>
                </div>
            </div>
        </div>
    </a>
</template>

<script>


import { library } from '@fortawesome/fontawesome-svg-core';
import { faCrown } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {mapGetters} from 'vuex';

library.add(faCrown);

export default {
    props: {
        user: {
            type: Object,
            required: true,
        },
        is_chat_creator: {
            type: Boolean,
            default: false,
        }
    },
    components: {
        FontAwesomeIcon
    },
    computed: {
        isOnline(){
            //console.log('isOnline id: ' + this.user.id + ' ' + (this.users_online.filter(item => item === this.user.id).length > 0));
            return this.users_online.filter(item => item === this.user.id).length > 0;
        },
        ...mapGetters(['users_online']),
    }
}
</script>

<style scoped>
    .user-item.creator-chat{
        order: -1 !important;
    }

    .creator-chat svg{
        color: #fcd975;
        font-size: 10px;
    }

    .user-status{
        position: relative;
        padding-left: 18px;
    }

    .user-status:after{
        content: '';
        display: block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        position: absolute;
        left: 2px;
        top: 50%;
        transform: translate(0, -50%);
    }

    .user-item{
        order: 1;
    }

    .user-item.user-online{
        order: 0;
    }

    .user-status.online:after{
        background-color: green;
    }

    .user-status.offline:after{
        background-color: red;
    }
</style>
