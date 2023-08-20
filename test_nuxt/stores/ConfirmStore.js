import { defineStore } from 'pinia'

export const useConfirmStore = defineStore('confirm', {
  state: () => ({
    message: '',
    action: null,
    visible: false,
  }),
  getters: {
    
    getСonfimMessage:(state) =>  state.message,
    getConfimAction:(state) => state.action,
    getConfimVisible:(state) =>state.visible,
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