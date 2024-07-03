import api from '@/services/api.js';

export const pageLoad = async (path, params) => {
    const res = await api.get(path, { params: { ...params } });
    return res;
}
