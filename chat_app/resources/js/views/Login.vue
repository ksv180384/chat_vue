<template>
  <div class="container vh-100">

    <div class="row h-100 align-items-center justify-content-center">
      <div class="col-md-7 col-lg-5s shadow bg-white rounded py-3 px-5">
        <h1 class="text-center">Авторизация</h1>

        <form @submit.prevent="submit">
          <div class="">
            <div class="form-group mt-3">
              <label class="form-label">Email</label>
              <input
                ref="input_email"
                v-model.trim="form.email"
                type="email"
                class="form-control"
                placeholder="Введите ваш email"
              />
            </div>

            <div class="form-group mt-3">
              <label class="form-label">Пароль*</label>
              <input
                v-model="form.password"
                type="password"
                class="form-control"
                placeholder="Введите пароль"
              />
            </div>

            <div class="mt-3 mb-3 form-check">
              <input
                v-model="form.remember"
                id="remember"
                type="checkbox"
                class="form-check-input"
              />
              <label class="form-check-label" for="remember">Запомнить*</label>
            </div>

            <div class="text-center my-3">
              <button type="submit" class="btn btn-primary" :disabled="isSubmitting">Вход</button>
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

<script setup>

//import { mapMutations } from 'vuex';
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useVuelidate } from '@vuelidate/core';
import { required, email, minLength, helpers } from '@vuelidate/validators';
import { getResponseErrorMessage, responseErrorNote } from '@/helpers/helpers.js';
import { login } from '@/services/user_service.js';

const router = useRouter();
const form = reactive({
  email: 'test@test.ru',
  password: 'password',
  remember: true,
});
const rules = computed(() => ({
  email: {
    required,
    email,
    minLength: minLength(2)
  },
  // password: {
  //   required,
  //   minLength: minLength(6)
  // },
}));
const errorMessage = ref('');
const isSubmitting = ref(false);

// const v$ = useVuelidate(rules, form);

const submit = async () => {
  const v$ = useVuelidate(rules, form);

  // console.log(v$);
  //
  // return;

  isSubmitting.value = true;

  try {
    const resLogin = await login(form.email, form.password);
    // const accessToken = resLogin.access_token;
    // const user = resLogin.user;

    // this.request = false;
    // localStorage.setItem('user_token', accessToken);
    // setUserDataToLocalStorage(user);
    // this.setUser(user);
    // if(this.remember){
    //     this.setAuthRemember(true);
    //     localStorage.setItem('remember', true);
    // }
    // api.defaults.headers.common['Authorization'] = 'Bearer ' + accessToken;
    //console.log(resLogin)
    router.push({ name: 'chats-list' });
  } catch (e) {
    form.password = '';

    if(e.response.status === 422){
      errorMessage.value =  getResponseErrorMessage(e);
    }else{
      responseErrorNote(e);
    }
  } finally {
    isSubmitting.value = false;
  }
}

// export default {
//   name: "Login",
//   setup (){
//     return { v$: useVuelidate() }
//   },
//   data(){
//     return {
//       email: '',
//       password: '',
//       remember: true,
//       request: false,
//       error_message: '',
//       errors: {
//         required: helpers.withMessage('Поле не должно быть пустым.', required),
//         email: helpers.withMessage('Некорректный email.', email),
//       }
//     }
//   },
//   mounted() {
//     this.$refs.input_email.focus();
//   },
//   validations(){
//     return {
//       email: { required: this.errors.required, email: this.errors.email },
//     }
//   },
//   methods: {
//     ...mapMutations('storeUser', ['setUser', 'setAuthRemember']),
//     async login(){
//       this.error_message = '';
//
//       this.v$.$touch();
//       if (this.v$.$error) return;
//
//       this.request = true;
//
//       try {
//         const resLogin = await login(this.email, this.password);
//         // const accessToken = resLogin.access_token;
//         // const user = resLogin.user;
//
//         this.request = false;
//         // localStorage.setItem('user_token', accessToken);
//         // setUserDataToLocalStorage(user);
//         // this.setUser(user);
//         // if(this.remember){
//         //     this.setAuthRemember(true);
//         //     localStorage.setItem('remember', true);
//         // }
//         // api.defaults.headers.common['Authorization'] = 'Bearer ' + accessToken;
//         this.$router.push({ name: 'chats-list' });
//       }catch (e) {
//         this.password = '';
//         this.request = false;
//
//         if(e.response.status === 422){
//           this.error_message =  getResponseErrorMessage(e);
//         }else{
//           responseErrorNote(e);
//         }
//       }
//     },
//   }
// }
</script>

<style scoped>

</style>
