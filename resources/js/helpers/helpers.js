// Получаем данные пользователя из хранилища
import store from "../store";
import api from "./api";

import { useToast } from "vue-toastification";

export const userData = () => {
    let userData = null;
    try{
        userData = JSON.parse(localStorage.getItem('user'));
    }catch (e) {

    }

    if(!userData){
        userData = {
            id: 0,
            name: '',
            avatar: '',
            avatar_src: '',
        };
    }

    return userData;
}

export const setUserDataToLocalStorage = (userData) => {
    try{
        localStorage.setItem('user', JSON.stringify(userData));
    }catch (e) {

    }
}

export const pageLoad = async (url) => {
    store.commit('setLoadPage', true);
    store.commit('setIsSiteNotWork', false);
    let res = '';
    try{
        res = await api.get(url);
        store.commit('setLoadPage', false);
    } catch (e) {
        res = e;
        store.commit('setLoadPage', false);
        store.commit('setIsSiteNotWork', true);
    }
    return res;
}

export const getResponseErrorMessage = (resError) => {

    const errors = resError?.response?.data?.errors;
    const message = resError?.response?.data?.message;

    if(!errors && message){
        return message;
    }

    if(!errors){
        return 'Ошибка';
    }

    const firstError = errors[Object.keys(errors)[0]];

    if(!firstError){
        return 'Ошибка';
    }

    return firstError[0] ?? 'Ошибка';
}

export const showNote = (message, type) => {
    const toast = useToast();
    toast[type](message);
}

export const responseErrorNote = (response) => {
    const errorMessage = getResponseErrorMessage(response);
    showNote(errorMessage, 'error');
}
