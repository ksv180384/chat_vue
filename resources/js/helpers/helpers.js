// Получаем данные пользователя из хранилища
import store from "../store";
import api from "./api";

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
    const res = await api.get(url);
    store.commit('setLoadPage', false);
    return res;
}

export const getResponseErrorMessage = (resError) => {
    const errors = resError?.response?.data?.errors;

    if(!errors){
        return 'Ошибка';
    }
    const firstError = errors[Object.keys(errors)[0]];

    if(!firstError){
        return 'Ошибка';
    }

    return firstError[0] ?? 'Ошибка';
}
