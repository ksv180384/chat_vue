<template>
    <h1>
        <router-link :to="`/chat/${$route.params.id}`">
            <FontAwesomeIcon icon="caret-left" />
        </router-link>
        Настройка чата
    </h1>
    <div class="settings-container">
        <ul class="list-group">
            <li class="list-group-item">
                <div class="form-check">
                    <input @change="changeSetting"
                           class="form-check-input"
                           type="checkbox"
                           value="show_notification_new_message"
                           id="checkboxShowNotification"
                           :checked="show_notification_new_message"
                    >
                    <label class="form-check-label" for="checkboxShowNotification">
                        Уведомлять о новых сообщениях
                    </label>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>

import { loadChatSettingsPage, changeSettingsChat } from '../../services/chat_service';
import {mapGetters, mapMutations} from "vuex";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {library} from "@fortawesome/fontawesome-svg-core";
import {faCaretLeft} from "@fortawesome/free-solid-svg-icons";
import {responseErrorNote} from "../../helpers/helpers";

library.add(faCaretLeft);

export default {
    name: "ChatUserOptions",
    components: {
        FontAwesomeIcon,
    },
    data(){
        return {
            show_notification_new_message: false
        }
    },
    mounted() {
        this.loadSettingsPage();
    },
    computed: {
        ...mapGetters('storeChatsList', ['chats']),
    },
    methods: {
        ...mapMutations('storeChatsList', ['changeChat']),
        async loadSettingsPage(){
            const chatId = this.$route.params.id;
            const resSettingsPage = await loadChatSettingsPage(chatId);

            this.show_notification_new_message = !!resSettingsPage.settings.show_notification_new_message
        },
        async changeSetting(e){
            const settingField = e.target.value;
            this[settingField] = !this[settingField];
            const chatId = +this.$route.params.id;

            const chatData = { setting: settingField, value: this[settingField] };

            try {
                const resChangeSettingsChat = await changeSettingsChat(chatId, chatData);

                const currentChat = this.chats.find(item => item.id === chatId);
                currentChat.settings[settingField] = +this[settingField];
                this.changeChat(currentChat);
            } catch (e) {
                this[settingField] = !this[settingField];
                responseErrorNote(e);
            }
        }
    },
}
</script>

<style scoped>
    .settings-container{
        max-width: 600px;
        margin: 0 auto;
    }
</style>
