<template>
    <div>
        <h1>
            <router-link to="/"><FontAwesomeIcon icon="caret-left" /></router-link>
            Профиль
        </h1>
        <form @submit.prevent="submit" action="">
            <div class="d-flex flex-row mt-3">
                <div class="load-file-block"
                     :class="{ 'drag-enter': avatar_drag_enter }"
                >
                    <div v-if="avatar || image_file" class="loaded-avatar">
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
                        <input type="text"
                               class="form-control"
                               placeholder="Имя"
                               v-model="name"
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

import {mapMutations, mapGetters} from "vuex";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCaretLeft, faTrash } from '@fortawesome/free-solid-svg-icons';
import {library} from "@fortawesome/fontawesome-svg-core";

import { loadProfileData, saveProfile, deleteAvatar } from '../../services/user_service';

library.add(faCaretLeft, faTrash);

export default {
    name: "Profile",
    components: {
        FontAwesomeIcon,
    },
    data(){
        return {
            name: this.$store.state.storeProfile.name,
            avatar: this.$store.state.storeProfile.avatar,
            avatar_src: this.$store.state.storeProfile.avatar_src,
            image_file: '',
            avatar_drag_enter: false
        }
    },
    computed: {
        ...mapGetters('storeProfile', [
            'name',
            'avatar',
            'avatar_src',
        ]),
    },
    methods: {
        ...mapMutations('storeProfile', ['setProfile']),
        ...mapMutations('storeUser', ['setUser']),
        async loadProfile(){
            const profileData = await loadProfileData();
            this.setProfile(profileData);
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
                this.$refs.img_avatar.setAttribute('src', reader.result);
            }.bind(this);
        },
        async submit(event){
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', this.name);
            formData.append('avatar', this.image_file);

            const resSaveProfile = await saveProfile(formData);
            this.setProfile(resSaveProfile.user);
            this.setUser(resSaveProfile.user);
        },
        removeImg(){
            this.image_file = '';
            this.$refs.inputImg.value = '';
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
            this.setProfile(resDeleteAvatar.user);
            this.setUser(resDeleteAvatar.user);
            this.avatar = resDeleteAvatar.user.avatar;
            this.avatar_src = resDeleteAvatar.user.avatar_src;
            this.image_file = '';
        }
    },
    mounted() {
        this.loadProfile();
    }
}
</script>

