<template>
  <div class="container vh-100">

    <div class="row h-100 align-items-center justify-content-center">
      <div class="col-md-7 col-lg-5s shadow bg-white rounded py-3 px-5">
        <h1 class="text-center">Регистрация</h1>

        <form @submit.prevent="registration">
          <div class="">
            <div class="form-group mt-3">
              <label class="form-label">Имя</label>
              <input ref="input_name"
                     type="text"
                     class="form-control"
                     placeholder="Введите ваше имя"
                     v-model="form.name"
                     @blur="v$.form.name.$touch"
              />
              <div v-if="v$.form.name.$error" class="form-text text-danger">
                {{ v$.form.name.$errors[0].$message }}
              </div>
            </div>
            <div class="form-group mt-3">
              <label class="form-label">Email</label>
              <input type="email"
                     class="form-control"
                     placeholder="Введите ваш email"
                     v-model="form.email"
                     @blur="v$.form.email.$touch"
              />
              <div v-if="v$.form.email.$error || error" class="form-text text-danger">
                {{ v$.form.email.$errors[0]?.$message || error }}
              </div>
            </div>
            <div class="form-group mt-3">
              <label class="form-label">Пароль</label>
              <input type="password"
                     class="form-control"
                     placeholder="Введите пароль"
                     v-model="form.password"
                     @blur="v$.form.password.$touch"
              />
              <div v-if="v$.form.password.$dirty && v$.form.password.$error"
                   class="form-text text-danger"
              >
                {{ v$.form.password.$errors[0].$message }}
              </div>
              <div v-if="v$.form.passwordConfirmation.$error"
                   class="form-text text-danger"
              >
                {{ v$.form.passwordConfirmation.$errors[0].$message }}
              </div>
            </div>
            <div class="form-group mt-3 mb-5">
              <label class="form-label">Подтвердите пароль</label>
              <input type="password"
                     class="form-control"
                     placeholder="Подтвердите пароль"
                     v-model="form.passwordConfirmation"
                     @blur="v$.form.passwordConfirmation.$touch"
              />
            </div>

            <div class="text-center my-3">
              <button class="btn btn-primary" :disabled="load_form">Зарегистрироваться</button>
            </div>

          </div>
        </form>
        <div class="my-4 text-center">
          <router-link to="/login">Авторизоваться</router-link>
        </div>

      </div>
    </div>
  </div>
</template>

<script>

import useVuelidate from '@vuelidate/core';
import { required, email, minLength, sameAs, helpers } from '@vuelidate/validators';
import api from "../helpers/api";
import {mapMutations} from "vuex";
import {getResponseErrorMessage, responseErrorNote, setUserDataToLocalStorage} from "../helpers/helpers";
import { registration } from '../services/user_service';

export default {
    setup (){
        return { v$: useVuelidate() }
    },
    data (){
        return {
            form: {
                name: '',
                email: '',
                password: '',
                passwordConfirmation: '',
            },
            load_form: false,
            errors: {
                required: helpers.withMessage('Поле не должно быть пустым.', required),
                email: helpers.withMessage('Некорректный email.', email),
                minLength: (length) => helpers.withMessage(({ $params }) => `Поле должно быть не короче ${$params.min} символов.`, minLength(length)),
                sameAs: (password) => helpers.withMessage('Неверно подтвержден пароль.', sameAs(password)),
            },
            error: '',
        }
    },
    mounted() {
        this.$refs.input_name.focus();
    },
    validations (){
        return {
            form:{
                name: {
                    required: this.errors.required,
                    minLength: this.errors.minLength(2)
                },
                email: { required: this.errors.required, email: this.errors.email },
                password: { required: this.errors.required, minLength: this.errors.minLength(6) },
                passwordConfirmation: { sameAs: this.errors.sameAs(this.form.password) }
            }
        }
    },
    methods: {
        ...mapMutations('storeUser', ['setUser']),
        async registration(){
            this.v$.$touch();
            if (this.v$.$error) return;

            this.load_form = true;
            const formParams = {
                name: this.form.name,
                email: this.form.email,
                password: this.form.password,
                password_confirmation: this.form.passwordConfirmation
            };

            try {
                const resRegistration = await registration(formParams);
                const accessToken = resRegistration.access_token;
                const user = resRegistration.user;

                this.error = '';
                this.load_form = false;
                localStorage.setItem('user_token', accessToken);
                setUserDataToLocalStorage(user);
                this.setUser(user);
                api.defaults.headers.common['Authorization'] = 'Bearer ' + accessToken;
                this.$router.push('/');
            }catch (e) {
                this.password = '';
                this.error = '';
                this.load_form = false;

                if(e.response.status === 422){
                    this.error =  getResponseErrorMessage(e);
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
