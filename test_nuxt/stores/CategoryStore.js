import { defineStore } from 'pinia'
export const useCategoryStore = defineStore('gategory', {
  state: () => ({
    // categorys: localStorage.getItem('categorys') ?
    //   JSON.parse(localStorage.getItem('categorys')):[],
    // iter: 1,
    // parent: localStorage.getItem('categoryParent') ?
    //   JSON.parse(localStorage.getItem('categoryParent')): null,
    // categorysFooter: localStorage.getItem('categorysFooter') ?
    //   JSON.parse(localStorage.getItem('categorysFooter')): null,
    categorys:[]
  }),
  getters: {

  },
  actions: {
    categorysToChange(categorys) {
      this.categorys = categorys
    },
   
  }
})