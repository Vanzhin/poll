import { 
  SET_SECTIONS,
  SET_SECTION
 } from './mutation-types.js'

 import axios from 'axios';
const state = () => ({
  sections: [],
  section: localStorage.getItem('section') ?
    JSON.parse(localStorage.getItem('section')):"",
})

const actions = {
  async getSectionTestIdDb({dispatch, commit}, {id, page = null, limit = 10 }){
    console.log("id - ",  id)
    const token = await dispatch("getAutchUserTokenAction")
    try{
      const config = {
        method: 'get',
        url: `/api/admin/section/test/${id}?limit=${limit}`,
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        }
      };
      if (page) {config.url = config.url + `&page=${page}`}
      await axios(config)
        .then(({data})=>{
          console.log("getSectionTestIdDb - ",  data)
          commit("SET_SECTIONS", data.section);
          dispatch("setPagination", data.pagination);
        })
    } catch (e) {
      console.log("getSectionTestIdDb - ",  e)
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getSectionTestIdDb', {id, page, limit})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  async deleteSectionIdDb({dispatch, commit}, {id, testId, activePage }){
    console.log("id - ",  id)
    const token = await dispatch("getAutchUserTokenAction")
    try{
      const config = {
        method: 'get',
        url: `/api/admin/section/${id}/delete`,
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        }
      };
      console.log(config)
      await axios(config)
        .then(({data})=>{
          console.log(data)
          dispatch('getSectionTestIdDb', {
            id: testId,
            // page: activePage,
            // limit: 10
          })
          
          dispatch('setMessage', data)
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('deleteSectionIdDb', {id, testId, activePage})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  async createSection({dispatch, commit}, {id, section, operation}){
    const token = await dispatch("getAutchUserTokenAction")
    try{
      const config = {
        method: 'post',
        url: `/api/admin/section/create`,
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json', 
          Authorization: `Bearer ${token}`
        },
        data: section
      };
      if (operation === 'edit') {
        config.url = `/api/admin/section/${id}/edit`
      }
      console.log(config)
      await axios(config)
        .then(({data})=>{
          console.log(data)
          dispatch('setMessage', data)
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('createSection', {id, section, operation})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  saveSelectSectionStore({dispatch, commit},{section}){
    commit("SET_SECTION", section)
  }
};

const getters = {
  getSections(state) {
    return state.sections 
  },
  getSectionTitle:(state)=>(id) =>{
    return state.sections.find(section => {
      return +section.id === +id}) 
  },
  getSection(state) {
    return state.section 
  },
}

const mutations = {
  [SET_SECTIONS](state, sections){
    state.sections = sections
  },
  [SET_SECTION](state, section){
    const parsed = JSON.stringify(section)
    localStorage.setItem('section', parsed);
    state.section = section
  },
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}