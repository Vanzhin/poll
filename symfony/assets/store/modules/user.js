import { 
  SET_IS_AUTCH_USER, SET_AUTCH_USER_TOKEN, SET_PAGE_NAME
} from './mutation-types.js'

const state = () => ({
  token:'',
  isAutchUser: false,
  page: ''
})

const actions = {
  async getLogInUser({ commit }, user) {
    try{
      const config = {
        method: 'post',
        url: '/api/login_check',
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json'
        },
        data: user  
      }
      await axios(config)
        .then((data)=>{
          console.log("getLogInUser - ",  data.token)
          commit("SET_AUTCH_USER_TOKEN", data.token);
          commit("SET_IS_AUTCH_USER")
        })
    } catch (e) {
      console.log("ошибка - ",e)
    }
  },
  
  setPage ({ commit }, page) {
    commit("SET_PAGE_NAME", page);
  },
  setIsAutchUser ({ commit }) {
    commit("SET_IS_AUTCH_USER")
  }
  
};

const getters = {
  getIsAutchUser(state) {
    return state.isAutchUser
  },
  getAutchUserToken(state) {
    return state.token
  },
  getPageName(state) {
    return state.page
  }
}

const mutations = {
  [SET_IS_AUTCH_USER] (state,  ) {
    console.log("SET_AUTCH_USER", )
    state.isAutchUser = true
  },
  [SET_AUTCH_USER_TOKEN] (state, token  ) {
    console.log("SET_AUTCH_USER_TOKEN", )
    state.token = token
  },
  [SET_PAGE_NAME] (state, page) {
    console.log("SET_PAGE_NAME", page)
    state.page = page
  }
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}