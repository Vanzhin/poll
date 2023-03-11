import { 
  SET_MESSAGE, 
  SET_MESSAGE_NULL,

} from './mutation-types.js'

const state = () => ({
    displayed: false,
    error: "",
    message: false,
  })
const actions = {
  setMessage({ commit }, message) {
    console.log("setMessage - ",  message)
    commit("SET_MESSAGE", message);
    setTimeout(()=> commit("SET_MESSAGE_NULL"), 3000)
  },
}
const getters = {
  getMessage(state) {
    console.log("getMessage - ",state.message)
    return state.message 
  },
}
const mutations = {
  [SET_MESSAGE] (state, message) {
    return state.message = { ...message};
  },
  [SET_MESSAGE_NULL] (state) {
    return state.message = false;
  },
}
export default {
    namespaced: false,
    state,
    actions,
    getters,
    mutations
  }