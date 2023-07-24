import { defineStore } from 'pinia'
export const useQuestionsStore = defineStore('questions', {
  state: () => ({
    
    questions:[],
    
  }),
  getters: {
    getQuestions: (state) => state.tests,
  },
  actions: {
    //запрос на получение вопросов пользователем при тестированиии
  async getQuestionsDb({id, slug}) {

  }
  }
})