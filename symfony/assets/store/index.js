import { createStore } from 'vuex'
import actions from './actions'
import mutations from './mutations'
import getters from './getters'
import state from "./state"
import modal from './modules/modal'
import questions from './modules/questions'
import tests from './modules/tests'
import user from './modules/user'
import sections from './modules/sections'
import areas from './modules/areas'
import tickets from './modules/tickets'
import category from './modules/category'
import pagination from './modules/pagination'
import confirm from './modules/confirm'
import loader from './modules/loader'
import results from './modules/results'
import metriks from './modules/metriks'
import crumbs from './modules/crumbs'
import searchQuery from './modules/searchQuery'
export default createStore({
  state,
  mutations,
  getters,
  actions,
  modules: {
    modal,
    questions,
    tests,
    user,
    sections,
    areas,
    tickets,
    category,
    pagination,
    confirm,
    loader,
    results,
    metriks,
    crumbs,
    searchQuery
  },
})