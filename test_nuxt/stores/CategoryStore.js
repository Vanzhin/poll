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
    getCategogys: (state) => state.categorys,
  },
  actions: {
    categorysToChange(categorys) {
      console.log('categorysToChange-',categorys)
      this.categorys = categorys
    },
    async getApiCategorys({ page = null, parentId = null, admin = null, limit = 6 }){
      const config = {
        method: 'get',
        url: `/api${admin ? '/admin': '' }/category?limit=6`,
        headers: { 
          Accept: 'application/json', 
        }
      };
      
      if (admin) { 
        config.headers.Authorization = `Bearer ${token}`
      }
  
      if (parentId) {
        config.url = config.url + `&parent=${parentId}`
      }
      
      if (page) {
        config.url = config.url + `&page=${page}`
      }

      try {
        this.userData = await api.post({ login, password })
        showTooltip(`Welcome back ${this.userData.name}!`)
      } catch (error) {
        showTooltip(error)
        // let the form component display the error
        return error
      }
    }
   
  }
})