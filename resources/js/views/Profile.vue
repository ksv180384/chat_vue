<template>
    <div>
        <h1>
            <router-link to="/"><FontAwesomeIcon icon="caret-left" /></router-link>
            Профиль
        </h1>
        <div class="d-flex flex-column align-items-center mt-5">
            <div class="w-75">
                <div class="form-floating mb-3">
                    <input type="text"
                           class="form-control"
                           placeholder="Имя"
                           v-model="name"
                    />
                    <label>Имя</label>
                </div>
                <div class="load-file-block mt-3">
                    <input type="file"/>
                </div>
            </div>
        </div>
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
    },
    mounted() {
        this.loadProfile();
    }
}
</script>

<style scoped>
    .load-file-block{
        width: 100%;
        height: 200px;
        border: 2px dashed #ced4da;
        border-radius: 6px;
        position: relative;
        text-align: center;
    }

    .load-file-block:before{
        content: 'Загрузить файл';
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
