import { defineStore } from 'pinia'

export const useConfirmStore = defineStore('confirm', {
  state: () => ({
    message: '',
    action: null,
    visible: false,
  }),
  getters: {
    
    getСonfirmMessage:(state) =>  state.message,
    getConfirmAction:(state) => state.action,
    getConfirmVisible:(state) =>state.visible,
  },
  actions: {
    setConfirmMessage(message) {
      this.message = message
    },
    setConfirmAction( action) {
      this.action = action
      this.message = ''
      setTimeout(()=> this.action = null, 1000)
    },
    setConfirmVisible(sign) {
      this.visible = sign
    },
   
   
  }
})