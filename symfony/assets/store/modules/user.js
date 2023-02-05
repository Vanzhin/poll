import { 
  SET_QUESTION,
} from './mutation-types.js'


const state = () => ({
 
  token:'',
  isAutchUser: false,
  
  
})

const actions = {
  async getLogInUser({ commit }) {
  try{
      await axios.get("/api/cources",{

      })
  } catch (e) {
    console.log(e);
    console.log("ошибка - ",e.response.data.message)
    dispatch(setErrorAction(e.response.data.message))
    return true
  }
  },
  
};

const getters = {
  getIsAutchUser(state) {
    return state.isAutchUser
  },
  
}

const mutations = {
  
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}