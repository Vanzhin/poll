import { 
  SET_SECTIONS,
 } from './mutation-types.js'

 import axios from 'axios';
const state = () => ({
  sections: [],
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
};

const getters = {
  getSections(state) {
    return state.sections 
  },
  getSectionTitle:(state)=>(id) =>{
    return state.sections.find(section => {
      return +section.id === +id}) 
  }
}

const mutations = {
  [SET_SECTIONS](state, sections){
    state.sections = sections
  }
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}