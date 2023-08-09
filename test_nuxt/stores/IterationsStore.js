import { defineStore } from 'pinia'
export const useIterationsStore = defineStore('iterations', {
  state: () => ({
    
    iterations: 1
  }),
  getters: {
    getIterations: (state) => state.categorys,
  },
  actions: {
    iterationsToChange(num) {
      console.log('iterationsToChangee-',num)
      this.iterations = num
    },
   
  }
})