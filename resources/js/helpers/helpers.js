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
