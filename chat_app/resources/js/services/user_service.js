import api from '@/services/api';
import { pageLoad } from '@/services/service';

export const login = async (email, password, remember) => {
    const formParams = { email, password, remember };
    return await api.post('/login', formParams);
}

export const registration = async (registrationData) => {
    return await api.post('/registration', registrationData);
}

export const saveProfile = async (profileData) => {
    return await api.post('/user/profile/update', profileData);
}

export const loadProfileData = async () => {
    const url = `/user/profile`;
    const res =  await pageLoad(url);
    return res.user;
}

export const deleteAvatar = async () => {
    return await api.delete('/user/profile/remove-avatar');
}
