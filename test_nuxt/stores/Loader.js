import { defineStore } from 'pinia'
export const useLoaderStore = defineStore('loader', {
  state: () => ({
    
    isLoader: false
  }),
  getters: {
    getIterations: (state) => state.isLoader,
  },
  actions: {
    setIsLoaderStutus(status) {
      console.log('setIsLoaderStutus - ', status)
      this.isLoader = status
    },
   
  }
})