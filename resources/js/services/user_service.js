import api from "../helpers/api";
import { pageLoad } from "../helpers/helpers";

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
