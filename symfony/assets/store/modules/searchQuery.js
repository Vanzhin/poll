import { 
  SET_SEARCH_RESULT,
} from './mutation-types.js'
import axios from 'axios';

const state = () => ({
  searchResult: []
  
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
          commit("SET_SEARCH_RESULT", data.test);
          
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
  
}

const mutations = {
  [SET_SEARCH_RESULT](state, search) {
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