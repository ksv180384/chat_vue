import api from "../helpers/api";

export const saveProfile = async (profileData) => {
    return await api.post('/user/profile/update', profileData);
}

export const loadProfileData = async () => {
    const res = await api.get(`/user/profile`);
    return res.user;
}

export const deleteAvatar = async () => {
    return await api.delete('/user/profile/remove-avatar');
}
