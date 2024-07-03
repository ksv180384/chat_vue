<template>
  <div>
    <h1>
      <router-link to="/"><FontAwesomeIcon icon="caret-left" /></router-link>
      Профиль <small>{{ userData.email }}</small>
    </h1>
    <form @submit.prevent="submit" action="">
      <div class="d-flex flex-row mt-3">
        <div
          class="load-file-block"
          :class="{ 'drag-enter': avatarDragEnter }"
        >
          <div v-if="avatarSrc" class="loaded-avatar">
            <img
              ref="img_avatar"
              :alt="userData.name"
              :src="avatarSrc"
            />
            <div @click.stop="removeAvatar" class="avatar-delete">
              <FontAwesomeIcon icon="trash" />
            </div>
          </div>
          <input
            ref="refInputImg"
            type="file"
            @dragenter="dragEnter"
            @dragleave="dragLeave"
            @drop="drop"
            @change="loadImage"
          />
        </div>
        <div class="flex-grow-1">
          <div class="form-floating mb-3">
            <input
              v-model="formData.name"
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

<script setup>
import { ref, computed, reactive } from 'vue';

import { useAuthUserStore } from '@/store/auth_user.js';
import { usePageStore } from '@/store/page.js';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCaretLeft, faTrash } from '@fortawesome/free-solid-svg-icons';
import { library } from '@fortawesome/fontawesome-svg-core';

import { saveProfile, deleteAvatar } from '@/services/user_service.js';
import { responseErrorNote } from '@/helpers/helpers.js';

library.add(faCaretLeft, faTrash);

const authUserStore = useAuthUserStore();
const pageStore = usePageStore();
const refInputImg = ref(null);
const userData = computed(() => pageStore.page || {});
const avatarSrc = ref(userData.value.avatar ? userData.value.avatar_src : null);
const avatarDragEnter = ref(false);
const formData = reactive({
  name: pageStore.page.name || '',
  avatar: null,
});

const dragEnter = () => {
  avatarDragEnter.value = true;
}
const dragLeave = () => {
  avatarDragEnter.value = false;
}
const drop = () => {
  avatarDragEnter.value = false;
}

const loadImage = (event) => {
  if(!event.target.files || event.target.files.length === 0){
    return true;
  }
  formData.avatar = event.target.files[0];
  avatarSrc.value = '';

  const reader = new FileReader();
  reader.readAsDataURL(formData.avatar);
  reader.onload = () => {
    avatarSrc.value = reader.result;
  };
}

const submit = async() => {
  const fData = new FormData();
  fData.append('_method', 'PUT');
  fData.append('name', formData.name);
  fData.append('avatar', formData.avatar || '');

  try {
    const res = await saveProfile(fData);
    // this.setUser(resSaveProfile.user);
    pageStore.page = res;
    formData.avatar = null;
    authUserStore.setUser({
      name: res.name,
      avatar: res.avatar_src,
    });
  } catch (e) {
    responseErrorNote(e);
  }
}

const removeAvatar = async () => {

  try{
    const res = await deleteAvatar();
    formData.avatar = null;
    avatarSrc.value = null;
    refInputImg.value = '';
    authUserStore.setUser({
      avatar: res.avatar_src,
    });
  } catch (e) {
    responseErrorNote(e);
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

