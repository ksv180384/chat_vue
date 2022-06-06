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
                           @change="uploadImage"
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

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCaretLeft, faTrash } from '@fortawesome/free-solid-svg-icons';
import {library} from "@fortawesome/fontawesome-svg-core";
import api from "../helpers/api";
import { mapGetters, mapState } from "vuex";

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
        ...mapState([
            'storeProfile'
        ]),

    },
    methods: {
        loadProfile(){
            api.get(`/user/profile`)
                .then(res => {
                    this.$store.commit('storeProfile/setProfile', res.user);
                })
        },
        uploadImage(event){
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
        submit(event){
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', this.name);
            formData.append('avatar', this.image_file);

            api.post('/user/profile/update', formData).then(res => {
                //this.name = res.user.name;
                this.$store.commit('storeProfile/setProfile', res.user);
            });
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
        removeAvatar(){
            api.delete('/user/profile/remove-avatar').then(res => {
                this.$store.commit('storeProfile/setProfile', res.user);
                this.avatar = res.user.avatar;
                this.avatar_src = res.user.avatar_src;
                this.image_file = '';
            });
        }
    },
    mounted() {
        this.loadProfile();
    }
}
</script>

