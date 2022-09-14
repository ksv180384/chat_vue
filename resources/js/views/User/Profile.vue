<template>
    <div>
        <h1>
            <router-link to="/"><FontAwesomeIcon icon="caret-left" /></router-link>
            Профиль <small>{{ email }}</small>
        </h1>
        <form @submit.prevent="submit" action="">
            <div class="d-flex flex-row mt-3">
                <div class="load-file-block"
                     :class="{ 'drag-enter': avatar_drag_enter }"
                >
                    <div v-if="avatar" class="loaded-avatar">
                        <img ref="img_avatar"
                             :alt="name"
                             :src="avatar_src"
                        />
                        <div @click.stop="removeAvatar" class="avatar-delete">
                            <FontAwesomeIcon icon="trash" />
                        </div>
                    </div>
                    <input ref="inputImg"
                           @dragenter="dragEnter"
                           @dragleave="dragLeave"
                           @drop="drop"
                           @change="loadImage"
                           type="file"
                    />
                </div>
                <div class="flex-grow-1">
                    <div class="form-floating mb-3">
                        <input v-model="name"
                               type="text"
                               class="form-control"
                               placeholder="Имя"
                        />
                        <label>Имя</label>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-outline-primary">Сохранить</button>
            </div>
        </form>
    </div>
</template>

<script>

import { mapMutations } from "vuex";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCaretLeft, faTrash } from '@fortawesome/free-solid-svg-icons';
import { library } from "@fortawesome/fontawesome-svg-core";

import { loadProfileData, saveProfile, deleteAvatar } from '../../services/user_service';

library.add(faCaretLeft, faTrash);

export default {
    name: "Profile",
    components: {
        FontAwesomeIcon,
    },
    data(){
        return {
            email: '',
            name: '',
            avatar: '',
            avatar_src: '',
            image_file: '',
            avatar_drag_enter: false
        }
    },
    mounted() {
        this.loadProfile();
    },
    methods: {
        ...mapMutations('storeUser', ['setUser']),
        async loadProfile(){
            const profileData = await loadProfileData();
            this.email = profileData.email;
            this.name = profileData.name;
            this.avatar = profileData.avatar;
            this.avatar_src = profileData.avatar_src;
        },
        loadImage(event){
            if(!event.target.files || event.target.files.length === 0){
                return true;
            }
            this.image_file = event.target.files[0];
            this.avatar_src = '';

            const reader = new FileReader();
            reader.readAsDataURL(this.image_file);
            reader.onload = function () {
                this.avatar = reader.result;
                this.avatar_src = reader.result;
            }.bind(this);
        },
        async submit(event){
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', this.name);
            formData.append('avatar', this.image_file);

            const resSaveProfile = await saveProfile(formData);
            this.setUser(resSaveProfile.user);
            this.image_file = '';
        },
        dragEnter(){
            this.avatar_drag_enter = true;
        },
        dragLeave(){
            this.avatar_drag_enter = false;
        },
        drop(){
            this.avatar_drag_enter = false;
        },
        async removeAvatar(){
            const resDeleteAvatar = await deleteAvatar();
            this.setUser(resDeleteAvatar.user);
            this.avatar = '';
            this.image_file = '';
            this.$refs.inputImg.value = '';
        }
    }
}
</script>

<style scoped>
h1>small{
    font-size: 16px;
}
.drag-enter .loaded-avatar{
    opacity: .2;
    transition: all .5s;
}
</style>

