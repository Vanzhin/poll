import { createStore } from 'vuex'
import actions from './actions'
import mutations from './mutations'
import getters from './getters'
import state from "./state";
import modal from './modules/modal'
import questions from './modules/questions'
import tests from './modules/tests'
import user from './modules/user'
import sections from './modules/sections'
import areas from './modules/areas'
import tickets from './modules/tickets'
import category from './modules/category';

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
    category
  },
})