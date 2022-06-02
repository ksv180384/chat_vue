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
                    <img v-if="image_file" @click.stop="removeImg" ref="img_avatar" src="" />
                    <img v-else-if="" @click.stop="removeImg" ref="img_avatar" src="" />
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
import { faCaretLeft } from '@fortawesome/free-solid-svg-icons';
import {library} from "@fortawesome/fontawesome-svg-core";
import api from "../helpers/api";
import {mapGetters} from "vuex";

library.add(faCaretLeft);

export default {
    name: "Profile",
    components: {
        FontAwesomeIcon,
    },
    data(){
        return {
            image_file: '',
            avatar_drag_enter: false
        }
    },
    computed: {
        name: {
            get(){
                return this.$store.state.storeProfile.name
            },
            set(val){
                this.$store.commit('setName', val)
            }
        },
    },
    methods: {
        loadProfile(){
            api.get(`/user/profile`)
                .then(res => {
                    this.$store.commit('setProfile', res.user);
                })
        },
        uploadImage(event){
            this.image_file = event.target.files[0];

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
                this.$store.dispatch('setProfile', res.user);
            })
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
        }
    },
    mounted() {
        this.loadProfile();
    }
}
</script>

<style scoped>
    .load-file-block{
        width: 200px;
        height: 200px;
        border: 2px dashed #ced4da;
        border-radius: 6px;
        position: relative;
        text-align: center;
        margin-right: 10px;
        overflow: hidden;
        transition: all .5s;
    }

    .load-file-block:before{
        content: 'Загрузить аватар';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .load-file-block>img{
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        object-fit: cover;
        z-index: 1;
    }

    .load-file-block input[type="file"]{
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .load-file-block.drag-enter{
        border-color: #00cd20;
        background-color: #efefef;
    }

</style>
