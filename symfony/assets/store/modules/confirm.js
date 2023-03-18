import { 
  SET_CONFIRM_MESSAGE, 
  SET_CONFIRM_ACTION,
  SET_CONFIRM_VISIBLE,
} from './mutation-types.js'

const state = () => ({
    message: '',
    action: null,
    visible: false,
   
  })
const actions = {
  setConfirmMessage({ commit }, message) {
    console.log("setConfirmMessage - ",  message)
    commit("SET_CONFIRM_MESSAGE", message);
  },
  
  setConfirmAction({ commit }, action) {
    console.log("setConfirmAction - ",  action)
    commit("SET_CONFIRM_ACTION", action);
    commit("SET_CONFIRM_MESSAGE", '');
    setTimeout(()=> commit("SET_CONFIRM_ACTION", null), 1000)
  },
  setConfirmVisible({ commit }, sign) {
    console.log("setConfirmVisible - ",  sign)
    commit("SET_CONFIRM_VISIBLE", sign);
  },


}
const getters = {
  getGonfimMessage(state) {
    console.log("getGonfimMessage - ",state.message)
    return state.message 
  },
  getGonfimAction(state) {
    console.log("getGonfimAction - ",state.action)
    return state.action 
  },
  getGonfimVisible(state) {
    console.log("getGonfimVisible - ",state.visible)
    return state.visible 
  },
}
const mutations = {
  [SET_CONFIRM_MESSAGE] (state, message) {
    state.message = message;
  },
  [SET_CONFIRM_ACTION] (state, action) {
    console.log("SET_CONFIRM_ACTION - ",action)
    state.action = action;
  },
  [SET_CONFIRM_VISIBLE] (state, sign) {

    state.visible = sign;
  },
}
export default {
    namespaced: false,
    state,
    actions,
    getters,
    mutations
  }