import { 
  SET_CATEGORYS, 
  SET_CATEGORY_TITLE, 
  SET_CATEGORY_DESCRIPTION,
  SET_CATEGORYS_PARENT,
 
 } from './mutation-types.js'
import axios from 'axios';

const state = () => ({
  categorys: localStorage.getItem('categorys') ?
  JSON.parse(localStorage.getItem('categorys')):[],
  iter: 1,
  parent: localStorage.getItem('categoryParent') ?
  JSON.parse(localStorage.getItem('categoryParent')): null
})

const actions = {
  async getCategorysDB({ dispatch, commit }, { page = null, parentId = null, admin = null }) {
    const config = {
      method: 'get',
      url: `/api${admin ? '/admin': '' }/category`,
      headers: { 
        Accept: 'application/json', 
        // Authorization: `Bearer ${token}`
      }
    };

    if (parentId) {
      config.url = config.url + `?parent=${parentId}`
    }
    if (page) {
      config.url = config.url + `${parentId ? "&" : "?"}` + `page=${page}`
    }
    console.log("getCategoryDB - ",  config)
  try{
    await axios(config)
      .then(({data})=>{
        console.log("getCategoryDB - ",  data)
        if (data.test) {dispatch("setTests", data.test)}
        else dispatch("setTests", null)
        commit("SET_CATEGORYS", data.children);
        commit("SET_CATEGORYS_PARENT", data.parent);
        dispatch("setPagination", data.pagination);
        // if (data.pagination) commit("SET_PAGINATION", data.pagination);
      })
  } catch (e) {
    console.log(e);
    dispatch("setTests", null)
    commit("SET_CATEGORYS", null);
    commit("SET_CATEGORYS_PARENT", null);
    dispatch("setPagination", null);
  }
  },
  setCategoryTitle({commit}, title){
    commit("SET_CATEGORY_TITLE", title);
  },
  setCategoryDescription({commit}, description){
    commit("SET_CATEGORY_DESCRIPTION", description);
  },
  async deleteCategoryDb({dispatch, commit}, {id, parentId, token, page}){
    const config = {
      method: 'get',
      url: `/api/admin/category/${id}/delete`,
      headers: { 
        Accept: 'application/json', 
        // Authorization: `Bearer ${token}`
      }
    };
    try{
      await axios(config)
        .then(({data})=>{
          console.log("deletCategoryDb - удалено",  data)
          dispatch("getCategorysDB",  { page: null , parentId: parentId });
          dispatch('setMessage', data)
        })
    } catch (e) {
      dispatch('setMessageError', e)
    }
  },
  async createCategory ({dispatch, commit}, {id, questionSend, token}){
    const data = new FormData(questionSend);
    for(let [name, value] of data) {
      console.dir(`${name} = ${value}`); // key1=value1, потом key2=value2
    }
    const config = {
      method: 'post',
      url: `/api/admin/category/create`,
      headers: { 
        Accept: 'application/json', 
        // Authorization: `Bearer ${token}`
      },
      data: data
    };
    try{
      await axios(config)
        .then(({data})=>{
          console.log("createCategory - создано",  data)
          dispatch('setMessage', data)
          // dispatch("getCategorysDB",  { page: null , parentId: id });
        })
    } catch (e) {
      console.log("Ошибка при создании",e);
      dispatch('setMessageError', e)
    }
  },
  async editCategory ({dispatch, commit}, {id, questionSend, token}){
    const data = new FormData(questionSend);
    for(let [name, value] of data) {
      console.dir(`${name} = ${value}`); // key1=value1, потом key2=value2
    }
    const config = {
      method: 'post',
      url: `/api/admin/category/${id}/edit`,
      headers: { 
        Accept: 'application/json', 
        // Authorization: `Bearer ${token}`
      },
      data: data
    };
    try{
      await axios(config)
        .then(({data})=>{
          console.log("createCategory - изменено",  data)
          dispatch('setMessage', data)
          dispatch("getCategorysDB", { page: null , parentId: id });
        })
    } catch (e) {
      console.log("Ошибка при изменении:", e);
      dispatch('setMessageError', e)
    }
  },
};

const getters = {
  getCategorys(state) {
    return state.categorys 
  },
  getCategoryTitle(state) {
    return state.parent ? state.parent.title : null
  },
  getCategoryDescription(state) {
    return state.parent.description
  },
  getCategoryParendId(state) {
    return state.parent ? state.parent.id : null
  },
  getCategory:(state) => (id)=> {
    return state.categorys.find(item => item.id === id) 
  },
}

const mutations = {
  [SET_CATEGORYS] (state, categorys) {
    if (categorys) {
    const parsed = JSON.stringify(categorys)
    localStorage.setItem('categorys', parsed)
    } else {
      localStorage.removeItem('categorys');
    }
    state.categorys = categorys
  },
  [SET_CATEGORY_TITLE] (state, title){
    state.title = title
  },
  [SET_CATEGORY_DESCRIPTION] (state, description){
    state.description = description
  },
  [SET_CATEGORYS_PARENT] (state, parent){
    if (parent) {
      const parsed = JSON.stringify(parent)
      localStorage.setItem('categoryParent', parsed)
    } else localStorage.removeItem('categoryParent');
    state.parent = parent
  },
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}