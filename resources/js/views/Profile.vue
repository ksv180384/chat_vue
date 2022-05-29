<template>
    <div>
        <h1>
            <router-link to="/"><FontAwesomeIcon icon="caret-left" /></router-link>
            Профиль
        </h1>
        <form @submit.prevent="submit" action="">
            <div class="d-flex flex-row mt-3">
                <div class="load-file-block">
                    <input type="file" @change="uploadImage" />
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
            image_file: null,
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
        },
        submit(event){
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', this.name);
            formData.append('avatar', this.image_file);

            api.post('/user/profile/update', formData).then(res => {
                this.$store.dispatch('setProfile', res.user);
            })
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
    }

    .load-file-block:before{
        content: 'Загрузить аватар';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .load-file-block input[type="file"]{
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

</style>
