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
  setMessage({ commit, state }, data) {
    
    let message = ''
    if (data.error) {
      
      message = {err: true, mes: `${data.message}!<hr>  ${data.error[0] ? data.error[0]:''}`}
    } else {
      
      message = {err: false, mes: data.message}
    }
    commit("SET_MESSAGE", message);
    setIntevalTime({ commit, state })
  },
  setMessageError({ commit, state  }, e) {
    // console.log("setMessageError - ",  e)
    let message = e.message ? `${e.message} <hr>` : ''
    if (e.response.data.message) {
      message += `${e.response.data.message}!<hr>`
    }
    if (e.response.data.error) {
      message += `${e.response.data.error}`
    }
    commit("SET_MESSAGE", {err:true, mes: message});
    setIntevalTime({commit, state} )
  },
  setMessageUser({ commit, state  }, message) {
    // console.log("setMessage - ",  message)
    commit("SET_MESSAGE", message);
    setIntevalTime({ commit, state  })
  },
  setMessageVisibleFalse({ commit }) {
    commit("SET_MESSAGE_NULL")
  },
}
function setIntevalTime ({ commit, state }){
  let start = new Date()
  let timerId = setInterval(() => {
    if ( !state.message ) {
      clearInterval(timerId)
    }
    let end = new Date()
    if ((end - start) > 10000) {
      commit("SET_MESSAGE_NULL")
    }
  }, 200);
}

const getters = {
  getMessage(state) {
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