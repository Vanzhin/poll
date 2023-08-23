import { defineStore } from 'pinia'
export const useLoaderStore = defineStore('loader', {
  state: () => ({
    
    isLoader: false
  }),
  getters: {
    getIterations: (state) => state.isLoader,
  },
  actions: {
    setIsLoaderStatus(status) {
      console.log('setIsLoaderStatus - ', status)
      this.isLoader = status
    },
   
  }
})