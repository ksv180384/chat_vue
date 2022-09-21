<template>
    <div class="container vh-100">

        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-md-7 col-lg-5s shadow bg-white rounded py-3 px-5">
                <h1 class="text-center">Авторизация</h1>

                <form @submit.prevent="login">
                    <div class="">
                        <div class="form-group mt-3">
                            <label class="form-label">Email</label>
                            <input ref="input_email"
                                   v-model.trim="email"
                                   @blur="v$.email.$touch"
                                   type="email"
                                   class="form-control"
                                   :class="{ 'border-danger': v$.email.$dirty && v$.email.$error }"
                                   placeholder="Введите ваш email"
                            />
                            <div v-if="v$.email.$dirty && v$.email.$error"
                                 class="form-text text-danger"
                            >
                                {{ v$.email.$errors[0].$message }}
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Пароль</label>
                            <input v-model="password"
                                   type="password"
                                   class="form-control"
                                   :class="{ 'border-danger': error_message }"
                                   placeholder="Введите пароль"
                            />
                            <div v-if="error_message"
                                 class="form-text text-danger"
                            >
                                {{ error_message }}
                            </div>
                        </div>
                        <div class="mt-3 mb-3 form-check">
                            <input id="remember" type="checkbox" class="form-check-input" v-model="remember">
                            <label class="form-check-label" for="remember">Запомнить</label>
                        </div>

                        <div class="text-center my-3">
                            <button type="submit" class="btn btn-primary" :disabled="request">Вход</button>
                        </div>

                    </div>

                    <div class="my-4 text-center">
                        <router-link to="/registration">Зарегистрироваться</router-link>
                    </div>
                </form>


            </div>
        </div>
    </div>
</template>

<script>

import { mapMutations } from "vuex";
import useVuelidate from "@vuelidate/core";
import { required, email, helpers } from "@vuelidate/validators";
import api from '../helpers/api';
import {setUserDataToLocalStorage, getResponseErrorMessage, responseErrorNote} from '../helpers/helpers';
import { login } from '../services/user_service';

export default {
    name: "Login",
    setup (){
        return { v$: useVuelidate() }
    },
    data(){
        return {
            email: '',
            password: '',
            remember: true,
            request: false,
            error_message: '',
            errors: {
                required: helpers.withMessage('Поле не должно быть пустым.', required),
                email: helpers.withMessage('Некорректный email.', email),
            }
        }
    },
    mounted() {
        this.$refs.input_email.focus();
    },
    validations(){
        return {
            email: { required: this.errors.required, email: this.errors.email },
        }
    },
    methods: {
        ...mapMutations('storeUser', ['setUser']),
        async login(){
            this.error_message = '';

            this.v$.$touch();
            if (this.v$.$error) return;

            this.request = true;

            try {
                const resLogin = await login(this.email, this.password, this.remember);
                const accessToken = resLogin.access_token;
                const user = resLogin.user;

                this.request = false;
                localStorage.setItem('user_token', accessToken);
                setUserDataToLocalStorage(user);
                this.setUser(user);
                api.defaults.headers.common['Authorization'] = 'Bearer ' + accessToken;
                this.$router.push('/');
            }catch (e) {
                this.password = '';
                this.request = false;

                if(e.response.status === 422){
                    this.error_message =  getResponseErrorMessage(e);
                }else{
                    responseErrorNote(e);
                }
            }
        },
    }
}
</script>

<style scoped>

</style>
