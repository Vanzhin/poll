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
    commit("SET_CONFIRM_MESSAGE", message);
  },
  setConfirmAction({ commit }, action) {
    commit("SET_CONFIRM_ACTION", action);
    commit("SET_CONFIRM_MESSAGE", '');
    setTimeout(()=> commit("SET_CONFIRM_ACTION", null), 1000)
  },
  setConfirmVisible({ commit }, sign) {
    commit("SET_CONFIRM_VISIBLE", sign);
  },
}
const getters = {
  getGonfimMessage(state) {
    return state.message 
  },
  getGonfimAction(state) {
    return state.action 
  },
  getGonfimVisible(state) {
    return state.visible 
  },
}
const mutations = {
  [SET_CONFIRM_MESSAGE] (state, message) {
    state.message = message;
  },
  [SET_CONFIRM_ACTION] (state, action) {
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