<template>
    <div v-if="notifications" class="notification-message-list">
        <div @click="toPageChat(notification.chat_room_id, notification.id)"
             v-for="notification of notifications"
             class="notification-message-item"
        >
            <img :src="notification.user.avatar_src" class="avatar"/>
            <div class="content">
                <div class="user-name">{{ notification.user.name }}</div>
                <div class="message">
                    {{ notification.message }}
                </div>
            </div>
            <div @click.stop="close(notification.id)" class="close">
                x
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapMutations} from "vuex";

export default {
    name: 'MessageNotification',
    mounted() {
        this.$store.state.socket.on('message', function(data){

            const chatId = data.chat_room_id;
            const currentChat = this.chats.find(item => item.id === chatId);

            if(
                currentChat.settings?.show_notification_new_message &&
                !(this.$route.name === 'Chat' && +this.$route.params.id === chatId)
            ){
                this.pushNotification(data);

                setTimeout(() => {
                    this.popNotification(data.id);
                }, 8000);
            }

        }.bind(this));
    },
    computed: {
        ...mapGetters('storeMessageNotifications', ['notifications']),
        ...mapGetters('storeChatsList', ['chats']),
    },
    methods: {
        ...mapMutations(
            'storeMessageNotifications',
            ['pushNotification', 'popNotification']
        ),
        toPageChat(chatId, messageId){
            this.$router.push(`/chat/${chatId}`);
            this.popNotification(messageId);
        },
        close(messageId){
            this.popNotification(messageId);
        }
    },
}
</script>

<style scoped>
    .notification-message-list{
        position: fixed;
        display: flex;
        flex-direction: column;
        bottom: 10px;
        right: 10px;
        z-index: 99;
    }

    .notification-message-item{
        position: relative;
        display: flex;
        flex-direction: row;
        border-radius: 4px;
        background-color: #6ec167;
        color: #f3fff4;
        width: 280px;
        padding: 6px 8px;
        margin-top: 10px;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 10%), 0 8px 10px -6px rgb(0 0 0 / 10%);
    }

    .avatar{
        width: 64px;
        height: 64px;
        border-radius: 4px;
        margin-right: 10px;
        object-fit: cover;
        object-position: top;
    }

    .content{
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .user-name{
        font-weight: bold;
    }

    .message{
        font-size: 14px;
    }

    .close{
        position: absolute;
        top: 4px;
        right: 4px;
        background-color: red;
        width: 24px;
        height: 24px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 4px;
        cursor: pointer;
        text-transform: uppercase;
        font-weight: bold;
    }
</style>
