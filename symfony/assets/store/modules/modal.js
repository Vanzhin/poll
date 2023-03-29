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
  setMessage({ commit }, data) {
    console.log("setMessage - ",  data)
    let message = ''
    if (data.error) {
      console.log("ошибка - ")
      message = {err: true, mes: `${data.message}!<hr>  ${data.error[0] ? data.error[0]:''}`}
    } else {
      console.log("сообщение - ")
      message = {err: false, mes: data.message}
    }
    commit("SET_MESSAGE", message);
    setTimeout(()=> commit("SET_MESSAGE_NULL"), 3000)
  },
  setMessageError({ commit }, e) {
    console.log("setMessageError - ",  e)
    let message = e.message ? `${e.message} <hr>` : ''
    if (e.response.data.message) {
      message += `${e.response.data.message}!<hr>`
    }
    if (e.response.data.error) {
      message += `${e.response.data.error}`
    }
    commit("SET_MESSAGE", {err:true, mes: message});
    setTimeout(()=> commit("SET_MESSAGE_NULL"), 3000)
  },
  setMessageUser({ commit }, message) {
    console.log("setMessage - ",  message)
    
    commit("SET_MESSAGE", message);
    setTimeout(()=> commit("SET_MESSAGE_NULL"), 3000)
  },
  setMessageVisibleFalse({ commit }) {
    commit("SET_MESSAGE_NULL")
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
    state.message = message;
  },
  [SET_MESSAGE_NULL] (state) {
    state.message = false;
  },
}
export default {
    namespaced: false,
    state,
    actions,
    getters,
    mutations
  }