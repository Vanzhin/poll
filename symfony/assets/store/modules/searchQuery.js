import { 
  SET_SEARCH_RESULT,
  SET_SEARCH_SING_TRUE,
  SET_SEARCH_SING_FALSE,
  SET_SEARCH_VALUE
} from './mutation-types.js'
import axios from 'axios';

const state = () => ({
  searchResult: [],
  searchSign: false,
  searchValue: ''
})

const actions = {
  async getSearchDb({dispatch, commit, state },{filter = '', limit = 1, page = 1, data = state.searchValue}) {
    
    const config = {
      method: 'post',
      url: `/api/search/test?limit=${limit}&page=${page}`,
      maxBodyLength: Infinity,
      headers: { 
        Accept: 'application/json', 
        'Content-Type': 'application/json',
      },
      data: JSON.stringify({
        "filter": {
          "title": data
        }
      })
    };
    if (filter !== ''){
      config.url += `&filter[title]=${filter}`
    }
    commit("SET_SEARCH_VALUE", data );
    try{
      await axios(config)
        .then(({data})=>{
          dispatch("setPagination", data.pagination);
          commit("SET_TESTS", data.test);
          commit("SET_SEARCH_SING_TRUE");
          
        })
    } catch (e) {
      console.log('ошибка - ', e)
      dispatch('setMessageError', e)
    }
  },
};

const getters = {
  getSearchResult(state) {
    return state.searchResult
  },
  
  getSearchSign(state) {
    return state.searchSign
  },
}

const mutations = {
  [SET_SEARCH_RESULT](state, search) {
    state.searchResult = search
  },
  [SET_SEARCH_SING_TRUE](state) {
    state.searchSign = true
  },
  [SET_SEARCH_SING_FALSE](state) {
    state.searchSign = false
  },
  [SET_SEARCH_VALUE](state, value) {
    state.searchValue = value
  },
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}