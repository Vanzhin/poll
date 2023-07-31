import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'
import { useLoaderStore } from './Loader'
import { useModalStore  } from './ModalStore'
export const useCategoryStore = defineStore('category', {
  state: () => ({
    // categorys: localStorage.getItem('categorys') ?
    //   JSON.parse(localStorage.getItem('categorys')):[],
    // iter: 1,
    // parent: localStorage.getItem('categoryParent') ?
    //   JSON.parse(localStorage.getItem('categoryParent')): null,
    // categorysFooter: localStorage.getItem('categorysFooter') ?
    //   JSON.parse(localStorage.getItem('categorysFooter')): null,
    categorys:[],
    parent: '',
    categorysFooter: null
  }),
  getters: {
    getCategogys: (state) => state.categorys,
    getCategoryTitle: (state) => state.parent.title
  },
  actions: {
    categorysToChange(categorys) {
      console.log('categorysToChange-',categorys)
      this.categorys = categorys
    },
    setParentCategory(parent){
      this.parent = parent
    },
    async getApiCategorys({ page = null, parentId = null, admin = null, limit = 6 }){
      const loader = useLoaderStore()
      // let url = `${urlApi}/api/category?limit=6${page > 1? '&page=' + page: ''}`
      let url = `${urlApi}/api/category`
      // if (admin) { 
      //   config.headers.Authorization = `Bearer ${token}`
      // }
      let query = { limit }
      if (parentId) { query.parent = parentId }
      
      if (page) { query.page = page }
      
      console.log(query)
      loader.setIsLoaderStatus(true)
      try {
        // this.userData = await api.post({ login, password })
        // const { data: sections, pending, error } = await useFetch(() =>  url,
        // {
        //   lazy: true,
        //   query:query,
        // })
        // let timerId = setInterval(() => {
        //   console.log('pending.value-',pending.value)
        //   if ( !pending.value) {
        //     clearInterval(timerId)
        //     console.log("sections", sections.value)
        //     this.categorys = sections.value.children
        //     console.log('error - ',sections.value.pagination.hasOwnProperty(error))
        //     const pagination = usePaginationStore()
        //     pagination.paginationsAll(sections.value.pagination)
        //     loader.setIsLoaderStatus(false)
        //     if (sections.value.parent) {
        //       this.parent = sections.value.parent
        //     }
        //   }
        // }, 200);
        const { data: sections, pending, error, refresh } = await useAsyncData(
         
          () => $fetch(url,{
            // lazy : true,
            query: query,
          })
        )
        console.log("sections", sections.value)
        this.categorys = sections.value.children
        const pagination = usePaginationStore()
            pagination.paginationsAll(sections.value.pagination)
            loader.setIsLoaderStatus(false)
            if (sections.value.parent) {
              this.parent = sections.value.parent
            }
      } catch (error) {
        console.log(error)
       
      }
    },
     //запрос категорий для футера
    async getCategorysDBFooter({ dispatch, commit }, ) {
      const modal = useModalStore()
      let url = `${urlApi}/api/category?limit=1000`
      
      const config = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
        }
      };
      
      try{
        const { data: sections, pending, error, refresh } = await useAsyncData(
          () => $fetch(url, config)
        )
        if (sections.value) {
          console.log('sections.value footer-', sections.value)
          this.categorysFooter = sections.value
        }
      } catch (e) { 
        console.log('footer e-', e)
      }
      
    }
  }
})