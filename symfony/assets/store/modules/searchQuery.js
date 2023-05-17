import { 
  SET_SEARCH_RESULT,
  SET_SEARCH_SING_TRUE,
  SET_SEARCH_SING_FALSE
} from './mutation-types.js'
import axios from 'axios';

const state = () => ({
  searchResult: [],
  searchSign: false
})

const actions = {
  async getSearchDb({dispatch, commit },{filter='',limit = 25, page = 1, data=''}) {
    const config = {
      method: 'get',
      url: `/api/search/test?limit=${limit}&page=${page}`,
      maxBodyLength: Infinity,
      headers: { 
        Accept: 'application/json', 
        'Content-Type': 'application/json',
      },
      data: JSON.stringify({"filter": {
          "title": "тест"
        }}
      )

      
    };
    if (filter !== ''){
      config.url += `&filter[title]=${filter}`
    }
    try{
      console.log(config)
      await axios(config)
        .then(({data})=>{
          console.log('data - ', data)
          
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
    console.log('есть нуль')
    state.searchSign = false
  },
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}