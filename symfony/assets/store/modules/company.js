import { 
  SET_COMPANY_LIST
} from './mutation-types.js'

import axios from 'axios';

const state = () => ({
  companyList: [],
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
      method: 'get',
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
      .then((response)=>{
        console.log(response)
        // commit(SET_COMPANY_LIST, data.list);
        
        // dispatch("setPagination", data.pagination);
        
      })
  } catch (e) {
    console.log(e) 
    // if (e.response.data.message === "Expired JWT Token") {
    //   await dispatch('getAuthRefresh')
    //   await dispatch('getCompanyListDB', { page, limit, filter, sort})
    // } else {
    //   commit(SET_COMPANY_LIST, null);
    //   dispatch("setPagination", null);
      dispatch('setMessageError', e)
    // }
  }
  },
  //запрос на создание компании
  async createCompany({dispatch, commit}, {id, conpanySend}){
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
}

const getters = {
  getCompanyList(state) {
    return state.companyList
  },
}
const mutations = {
  [SET_COMPANY_LIST] (state, companyList){
    state.companyList = companyList
  },
}
export default {
    namespaced: false,
    state,
    actions,
    getters,
    mutations
  }