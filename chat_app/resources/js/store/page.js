import { defineStore } from 'pinia';

const initPage = {};

export const usePageStore = defineStore('pageStore', {
  state: () => ({
    isLoading: false,
    page: initPage,
  }),
  actions: {
    setPageData(pageData){
      this.page = pageData;
    },
    clearData(){
      this.page = initPage;
    }
  }
});
