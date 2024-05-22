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

export const getAuthToken = () => {
    const t = localStorage.getItem('user_token');
    return t ? t : '';
}

export const removeUserData = (store) => {
    store.commit('storeUser/setUser', null);
    store.commit('storeUser/setAuthRemember', false);
    //store.getters.socket.disconnected();
    removeLocalStorageUserData();
}

export const removeLocalStorageUserData = () => {
    localStorage.removeItem('user_token');
    localStorage.removeItem('user');
    localStorage.removeItem('remember');
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
