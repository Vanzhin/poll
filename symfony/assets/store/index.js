import { createStore } from 'vuex'
import actions from './actions'
import mutations from './mutations'
import getters from './getters'
import state from "./state";
import modal from './modules/modal'
import questions from './modules/questions'
import tests from './modules/tests'

export default createStore({
  state,
  mutations,
  getters,
  actions,
  modules: {
    modal,
    questions,
    tests,
  },
})