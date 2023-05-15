import { 

} from './mutation-types.js'


const state = () => ({
  searchResult: []
  
})

const actions = {
  getCources({ commit }) {
    axios
      .get("/api/cources")
      .then((res) => {
        commit("GET_COURCES", res.data.raw);
      })
      .catch((err) => {
       
      });
  },
  getQuestion(){}
};

const getters = {
  getSearchResult(state) {
    return state.searchResult
  },
  
}

const mutations = {
  [GET_COURCES](state, search) {
    state.searchResult = search
  },
  
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}