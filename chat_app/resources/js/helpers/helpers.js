import { useToast } from 'vue-toastification';

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
