import { 
  SET_LOADER_TOGGLE,
  SET_LOADER_STATUS,
} from './mutation-types.js'

const state = () => ({
  isLoader: false,
})

const actions = {
  setIsLoaderToggle({ commit }){
    commit("SET_LOADER_TOGGLE")
  },
  setIsLoaderStatus({ commit }, {status}){
    commit("SET_LOADER_TOGGLE", status)
  },
}

const getters = {
  getIsLoaderQuestions(state) {
    console.log("isLoader ", state.isLoader)
    return state.isLoader 
  },
}
const mutations = {
  [SET_LOADER_TOGGLE] (state, ) {
    console.log("SET_LOADER_TOGGLE", state.isLoader)
    state.isLoader = !state.isLoader
  },
  [SET_LOADER_STATUS] (state, status) {
    console.log("SET_LOADER_TOGGLE", state.isLoader)
    state.isLoader = status
  },
}
export default {
    namespaced: false,
    state,
    actions,
    getters,
    mutations
  }