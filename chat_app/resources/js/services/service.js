import store from '@/store';
import api from '@/services/api.js';

export const pageLoad = async (path, params) => {
    store.commit('setLoadPage', true);
    const res = await api.get(path, { params: { ...params } });
    store.commit('setLoadPage', false);
    return res;
}
