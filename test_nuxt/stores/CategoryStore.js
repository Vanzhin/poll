import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'
import { useLoaderStore } from './Loader'
import { useModalStore  } from './ModalStore'
import { useUserStore  } from './UserStore'
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
    allСategories: null
  }),
  getters: {
    getCategorys: (state) => state.categorys,
    getCategorysIs: (state) => state.categorys.length > 0,
    getCategoryTitle: (state) => state.parent.title,
    getCategoryAlias: (state) => state.parent.alias,
    getCategoryDescription: (state) => state.parent.description || '',
    getCategoryRobots: (state) => state.parent.robots || 'max-image-preview:large',
    getCategoryCanonical: (state) => state.parent.canonical || '',
    getCategorySeoDescription: (state) => state.parent.descriptionSeo || '',
    getFooterСategories : (state) => state.allСategories ? state.allСategories.slice(0, 6): null,
    getCategoriesForDropDown: (state) => state.allСategories,
    getCategoryParendId: (state) => state.parent.id || null
  },
  actions: {
    categorysToChange(categorys) {
      console.log('categorysToChange-',categorys)
      this.categorys = categorys
    },
    setParentCategory(parent){
      this.parent = parent
    },
    async getApiCategorys({ page = null, parentId = null, admin = false, limit = 6 }){
      let url = `${urlApi}/api/category`
      let  params = {
        method: 'GET',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        params: { limit }
      }
      
      if (parentId) {params.params.parent = parentId }
      if (page) { params.params.page = page }

      const sections = await setUseAsyncFetch({ url, params, token: admin })
      if (sections.children) {
        this.categorys = sections.children
      }
      
      const pagination = usePaginationStore()
      pagination.paginationsAll(sections.pagination)
      
      if (sections.parent) {
        this.parent = sections.parent
      }
      
    },
     //запрос категорий для футера
    async getCategorysDBFooter() {
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
          this.allСategories = sections.value.children
        } else if (error.value) {
          modal.setMessageError(error.value)
        } else {
          
        }
      } catch (e) { 
        console.log('footer e-', e)
      }
      
    },
    //удаление категории из БД
    async deleteCategoryDb({id, parentId, page}){
      let url = `${urlApi}/api/admin/category/${id}/delete`
      let  params = {
        method: 'GET',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        
      }
      
      if (parentId) {params.params.parent = parentId }
      
      const modal = useModalStore()
      const sections = await setUseAsyncFetch({ url, params, token: true })
      

      await this.getApiCategorys({
        admin: true, 
        page,
        parentId,
      })
      modal.setMessage( sections )
    },
  }
})