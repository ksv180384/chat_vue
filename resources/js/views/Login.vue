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
                     type="email"
                     class="form-control"
                     :class="{ 'border-danger': error}"
                     placeholder="Введите ваш email"
                     v-model="email"
              />
              <div v-if="error" class="form-text text-danger">{{ error }}</div>
            </div>
            <div class="form-group mt-3">
              <label class="form-label">Пароль</label>
              <input type="password" class="form-control" :class="{ 'border-danger': error}" placeholder="Введите пароль" v-model="password">
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

import {mapMutations} from "vuex";
import api from '../helpers/api';
import { setUserDataToLocalStorage } from './../helpers/helpers';

export default {
    name: "Login",
    data(){
        return {
            email: '',
            password: '',
            remember: true,
            request: false,
            error: null
        }
    },
    mounted() {
        this.$refs.input_email.focus();
    },
    methods: {
        ...mapMutations('storeUser', ['setUser']),
        login(){
            this.error = null;
            this.request = true;
            const formParams = { email: this.email, password: this.password, remember: this.remember };
            api.post('/login', formParams)
                .then(res => {
                    this.request = false;
                    localStorage.setItem('user_token', res.access_token);
                    //localStorage.setItem('user', JSON.stringify(res.user));
                    setUserDataToLocalStorage(res.user);
                    this.setUser(res.user);
                    api.defaults.headers.common['Authorization'] = 'Bearer ' + res.access_token;
                    this.$router.push('/');
                }).catch(error => {
                    // handle error
                    this.password = '';
                    this.request = false;
                    this.error = error.response.data.message;
                });
        },
    }
}
</script>

<style scoped>

</style>
