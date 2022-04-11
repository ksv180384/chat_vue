import axios from 'axios';
import router from '../router';

const api = axios.create({
    baseURL: process.env.MIX_APP_URL + '/api/v1',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }
});

api.interceptors.request.use(
    (config) => {
        // задаем jwt токен авторизации
        config.headers['Authorization'] = 'Bearer ' + (localStorage.getItem('user_token') ?? null);
        return config;
    },
    (error) => {
        return Promise.reject(error);
    });

api.interceptors.response.use(function (response) {
    return response.data;
}, function (error) {
    // Если ошибка аворизации, то затираем токен и редирект на главную
    if(error.response.status === 401){
        localStorage.removeItem('user_token');
        api.defaults.headers.common['Authorization'] = null;
        router.push('/login');
    }
    return Promise.reject(error);
});

export default api;
