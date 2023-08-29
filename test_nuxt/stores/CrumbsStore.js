import { defineStore } from 'pinia'

export const useCrumbsStore = defineStore('crumbs', {
  state: () => ({
    crumbs:[],
    urlApi: useRuntimeConfig().public.urlApi,
  }),
  getters: {
    getCrumbs: (state) => state.crumbs,
    getCrumbsLendch: (state) => state.crumbs.length-1
  },
  actions: {
    async addIteration(iter) {
      this.crumbs.push(iter)
    },
    nullIteration() {
      this.crumbs=[
        {
          title:'Разделы',
          link: '/',
          active: false
        }
      ]
    },
    async setCrumbsAddTests(id){
      this.crumbs.at(-1).title += ' Тесты'
      this.crumbs.at(-1).link = `/area/${id}`
    },

    async setIterationsCrumbs(result){
      this.nullIteration()
      const provisional = []
      function generationOfCrumbs (crumbs) {
        provisional.push({
          id: crumbs.id,
          alias: crumbs.alias
        })
        if (crumbs.parent) {
          generationOfCrumbs(crumbs.parent)
        }
        return provisional
      }
      const cbumbsIter = generationOfCrumbs(result)
      for (let i = cbumbsIter.length - 1; i >= 0; i-- ){
        this.addIteration({
          title:`/${cbumbsIter[i].alias}`,
          link: `/iter/${cbumbsIter.length - i}/group/${cbumbsIter[i].id}`,
          active: false
        })
      }
    },

    async getCategoryCrumbsDB(id){
      let url = `${this.urlApi}/api/category/${id}/breadcrumbs`
      const params = {
        method: 'GET',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      }
      const result = await setUseAsyncFetch({ url, params, token: false })
      if (result.data) {
        this.setIterationsCrumbs(result.data)
      }
    },

    async getTestCrumbsDB(id){
      let url = `${this.urlApi}/api/test/${id}/breadcrumbs`
      const params = {
        method: 'GET',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      }
      const result = await setUseAsyncFetch({ url, params, token: false })
      await this.setIterationsCrumbs(result.data.category)
      this.setCrumbsAddTests(result.data.category.id)
      this.addIteration({
        title:`/${result.data.alias}`,
        link: `/test/${result.data.id}`,
        active: false
      }) 
      
    },

    async getTicketCrumbsDB(id){
      let url = `${this.urlApi}/api/ticket/${id}/breadcrumbs`
      const params = {
        method: 'GET',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      }
      const result = await setUseAsyncFetch({ url, params, token: false })
      await this.setIterationsCrumbs(result.data.test.category)
      await this.setCrumbsAddTests(result.data.test.category.id)
      await this.addIteration({
        title:`/${result.data.test.alias}`,
        link: `/test/${result.data.test.id}`,
        active: false
      }) 
      this.addIteration({
        title:`/Билет №${result.data.title}`,
        link: `/test/${result.data.test.id}/ticket/${result.data.id}`,
        active: false
      }) 
    }
  }
})