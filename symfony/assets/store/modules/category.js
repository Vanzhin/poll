import { 
  SET_CATEGORYS, 
  SET_PAGINATION, 
  SET_CATEGORY_TITLE, 
  SET_CATEGORY_DESCRIPTION,
  SET_TEST,
 } from './mutation-types.js'
import axios from 'axios';

const state = () => ({
  categorys: [],
  pagination:[],
  question:{},
  iter: 1,
  title: "",
  description: ""
})

const actions = {
  async getCategorysDB({ dispatch, commit }, { page = null , parentId = null }) {
    const config = {
      method: 'get',
      url: "/api/category",
      headers: { 
        Accept: 'application/json', 
        // Authorization: `Bearer ${token}`
      }
    };
    if (parentId) {
      config.url = config.url + `?parent=${parentId}`
    }
    if (page) {
      config.url = config.url + `?page=${page}`
    }
    
  try{
    await axios(config)
      .then(({data})=>{
        console.log("getCategoryDB - ",  data)
        if (data.test) dispatch('setTest', data.test)
        commit("SET_CATEGORYS", data.category);
        if (data.pagination) commit("SET_PAGINATION", data.pagination);
      })
  } catch (e) {
    console.log(e);
  }
  },
  setCategoryTitle({commit}, title){
    commit("SET_CATEGORY_TITLE", title);
  },
  setCategoryDescription({commit}, description){
    commit("SET_CATEGORY_DESCRIPTION", description);
  }
};

const getters = {
  getCategorys(state) {
    return state.categorys 
  },
  getPagination(state) {
    return state.pagination 
  },
  getPageActive(state) {
    return state.pageActive
  },
  getCategoryTitle(state) {
    return state.title
  },
  getCategoryDescription(state) {
    return state.description
  },
  
}

const mutations = {
  [SET_CATEGORYS] (state, categorys) {
    state.categorys = categorys
  },
  [SET_PAGINATION] (state, pagination) {
    const pagin = Array.from({length:pagination.totalPages}).map((inp, index) => { 
      return {label: index + 1, active: index + 1 === pagination.currentPage
      }})
      console.log(pagin)
    state.pagination = pagin
    state.pageActive = pagination.currentPage
  },
  [SET_CATEGORY_TITLE] (state, title){
    state.title = title
  },
  [SET_CATEGORY_DESCRIPTION] (state, description){
    state.description = description
  },
 
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}