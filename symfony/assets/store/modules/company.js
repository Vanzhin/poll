import { 
  SET_COMPANY_LIST,
  SET_COMPANY
} from './mutation-types.js'

import axios from 'axios';

const state = () => ({
  companyList: [],
  company: null, 
  companyAdmin: null
})

const actions = {
  //запрос на получение списка компаний
  async getCompanyListDB({ dispatch, commit }, { page = 1, limit = 10, filter = {}, sort = {"title": "ASC"} }) {
    const token = await dispatch("getAutchUserTokenAction")
    

    let data ={
      "filter": filter,
      "sort": sort
    }
    
    const config = {
      method: 'post',
      maxBodyLength: Infinity,
      url: `/api/admin/company/list?page=${page}&limit=${limit}`,
      headers: { 
        'Content-Type': 'application/json', 
        Authorization: `Bearer ${token}`
      },
      data : data
    };
    
    
    console.log(config) 
  try{
    await axios(config)
      .then(({data})=>{
        console.log(data)
        commit(SET_COMPANY_LIST, data.data.list);
        
        dispatch("setPagination", data.data.pagination);
        
      })
  } catch (e) {
    console.log(e) 
    if (e.response.data.message === "Expired JWT Token") {
      await dispatch('getAuthRefresh')
      await dispatch('getCompanyListDB', { page, limit, filter, sort})
    } else {
      commit(SET_COMPANY_LIST, null);
      dispatch("setPagination", null);
      dispatch('setMessageError', e)
    }
  }
  },
  //запрос на создание компании
  async createCompanyDb({dispatch, commit}, {id, conpanySend}){
    const token = await dispatch("getAutchUserTokenAction")
    const data = new FormData(conpanySend);
    let dataJSON = {}
    for(let [name, value] of data) {
      // console.dir(`${name} = ${value}`); 
      dataJSON[name] = value
    }

    const config = {
      method: 'post',
      url: `/api/admin/company`,
      headers: { 
        Accept: 'application/json', 
        Authorization: `Bearer ${token}`
      },
      data: dataJSON
    };
    console.log(config)
    try{
      await axios(config)
        .then(({data})=>{
          console.log(data)
          dispatch('setMessage', data)
          // dispatch("getCategorysDB",  { page: null , parentId: id });
        })
    } catch (e) {
      console.log(e)
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('createCompany', {id, conpanySend})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  //запрос на получение данных компании
  async getCompanyIdDB({dispatch, commit}, {id}){
    const token = await dispatch("getAutchUserTokenAction")
    const config = {
      method: 'get',
      url: `/api/admin/company/${id}`,
      headers: { 
        Accept: 'application/json', 
        Authorization: `Bearer ${token}`
      },
      
    };
    console.log(config)
    try{
      await axios(config)
        .then(({data})=>{
          console.log(data)
          commit(SET_COMPANY, data.data);
        })
    } catch (e) {
      console.log(e)
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getCompanyIdDB', {id})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  // запрос на редактирование данных компании
  async editCompanyDb({dispatch, commit}, {id, conpanySend}){
    const token = await dispatch("getAutchUserTokenAction")
    const data = new FormData(conpanySend);
    let dataJSON = {}
    for(let [name, value] of data) {
      // console.dir(`${name} = ${value}`); 
      dataJSON[name] = value
    }

    const config = {
      method: 'put',
      url: `/api/admin/company/${id}`,
      headers: { 
        Accept: 'application/json', 
        Authorization: `Bearer ${token}`
      },
      data: dataJSON
    };
    console.log(config)
    try{
      await axios(config)
        .then(({data})=>{
          console.log(data)
          dispatch('setMessage', data)
          // dispatch("getCategorysDB",  { page: null , parentId: id });
        })
    } catch (e) {
      console.log(e)
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('editCompany', {id, conpanySend})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  // запрос на удаление компании
  async deleteCompanyDb({dispatch}, {id}){
    const token = await dispatch("getAutchUserTokenAction")
   

    const config = {
      method: 'delete',
      url: `/api/admin/company/${id}`,
      headers: { 
        Accept: 'application/json', 
        Authorization: `Bearer ${token}`
      },
      
    };
    console.log(config)
    try{
      await axios(config)
        .then(({data})=>{
          console.log(data)
          // dispatch('setMessage', {message:'Удаление компании из БД прошло успешно.'})
          dispatch('setMessage', data.message)
          dispatch("getCompanyListDB",  { });
        })
    } catch (e) {
      console.log(e)
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('deleteCompanyDb', {id, conpanySend})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  // сохранение компании в сторе
  setCompanyStore({dispatch, commit}, {company}){
    commit(SET_COMPANY, company);
  },

  getAdminCompanyAction({state}) {
    return state.companyAdmin ?  state.companyAdmin :''
  },
}

const getters = {
  getCompanyList(state) {
    return state.companyList
  },
  getCompany(state) {
    return state.company
  },
  getUserListCompany(state) {
    return state.company ? state.company.users : ''
  },
  getAdminCompany(state) {
    
    return state.companyAdmin ? state.companyAdmin : ''
  },
  
}
const mutations = {
  [SET_COMPANY_LIST] (state, companyList){
    state.companyList = companyList
  },
  [SET_COMPANY] (state, company){
    console.log(company)
    state.company = company
    const admin = state.company.users.find((element)=>{
      return element.roles.includes('Администратор')
    })
    console.log(admin)
    
    state.companyAdmin = admin.email
  },

}
export default {
    namespaced: false,
    state,
    actions,
    getters,
    mutations
  }