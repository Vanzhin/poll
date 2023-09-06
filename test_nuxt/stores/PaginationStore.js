import { defineStore } from 'pinia'
export const usePaginationStore = defineStore('pagination', {
  state: () => ({
    activePage: 1,
    paginations:[],
    totalItemsPage: 0,
    paginVisible: 10,
    paginLeft: 1,
    paginRight: 10,
    totalPages: 1,
    totalItem: null,
    url:''
  }),
  getters: {
    getTotalItemsPage: (state) => state.totalItemsPage,
    getActivePage: (state) => state.activePage
  },
  actions: {
    activePageToChange(page) {
      this.activePage = page
    },
    paginationsAll(pagination) {
      console.log(pagination)
      if (!pagination || ("error" in pagination) || pagination.totalPages < 2 ) {
        this.paginations =[]
        this.activePage = 1
        console.log('обнулил пагинацию')
        return
      }
      let  pagin = []
      
      this.totalItemsPage = pagination.limit
      this.activePage = pagination.currentPage
      this.totalPages = pagination.totalPages
      this.totalItem = pagination.totalItem

      if (pagination.totalPages <= this.paginVisible){
        const len =  pagination.totalPages
        this.paginRight = pagination.totalPages
        pagin = Array.from({length: len}).map((inp, index) => { 
          return {label: index + 1 , active: index + 1  === this.activePage ? 
            true : false,
          }})
      } else {
        if (this.activePage === 1 ) {this.paginRight = this.paginVisible }
        if ( this.paginRight < this.activePage ) { 
          if ( (this.activePage + this.paginVisible - 1) < this.totalPages){
            this.paginRight = this.activePage + this.paginVisible - 1
          } else {
            this.paginRight = this.totalPages
          }
          this.paginLeft = this.paginRight - (this.paginVisible - 1)
        } else if (this.paginLeft > this.activePage ) {
          if ( (this.activePage - this.paginVisible + 1) > 0){
            this.paginLeft = this.activePage - (this.paginVisible - 1)
          } else {
            this.paginLeft = 1
          }
          this.paginRight = this.paginLeft + this.paginVisible - 1
        } 
        const len = this.paginVisible
        pagin = Array.from({length: len}).map((inp, index) => { 
          return {label: this.paginLeft + index , active: this.paginLeft + index === this.activePage ? 
            true : false,
        }})
      }




      this.paginations = pagin
    },
    urlToChange(url) {
      this.url = url.trim()=== '/'? '' : url.trim()
    }
  }
})