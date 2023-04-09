import { 
  
  SET_AUTH_ACCOUNT,
} from './mutation-types.js'

import axios from 'axios';

const state = () => ({
 
  result: [],
  
  
})

const actions = {
   
 
  // получение данных статистики
  async getAuthAccountDb({dispatch, commit, state }) {
    const token = await dispatch("getAutchUserTokenAction")
    try {
      const config = {
        method: 'get',
        url: '/api/auth/account',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
      }
      await axios(config)
        .then((data)=>{
          console.log("getAuthAccountDb - ",  data.data.results)
          commit("SET_AUTH_ACCOUNT", data.data.results);
        })
    } catch (e) {
      const err = e.response.data.message
      console.log("ошибка - ",e)
      if (err === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getAuthAccountDb')
      }else {
        dispatch('setMessageError', e)
      }
    }
  },
  // получение данных статистики развернутый
  async getAuthAccountResultsDb({dispatch, commit, state }) {
    const token = await dispatch("getAutchUserTokenAction")
    try {
      const config = {
        method: 'get',
        url: '/api/auth/result',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
      }
      await axios(config)
        .then((data)=>{
          console.log("getAuthAccountResultsDb - ",  data)
          commit("SET_AUTH_ACCOUNT", data.data.results);
          dispatch("setPagination", data.pagination);
        })
    } catch (e) {
      const err = e.response.data.message
      console.log("ошибка - ",e)
      if (err === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getAuthAccountResultsDb')
      }else {
        dispatch('setMessageError', e)
      }
    }
  },
  // получение отчета в xml
  async getResultsXmlDb({dispatch, commit, state },{id} ) {
    const token = await dispatch("getAutchUserTokenAction")
    try {
      const config = {
        method: 'post',
        url: `/api/auth/result/${id}/report`,
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        data:{
          "format": "xml"
        }
      }
      await axios(config)
        .then((data)=>{
          console.log("getResultsXmlDb - ",  data.data)
          //let blob = new Blob([data.data], {type: "text/plain"});
          //const parser = new DOMParser();
         // const doc = parser.parseFromString([data.data], "application/xml");
        //  let person = document.createElement('RegistrySet');
        //  let name = document.createElement('RegistryRecord');
        //  let surname = document.createElement('Worker');
        //  person.appendChild(name);
        //  person.appendChild(surname);
         
        //  let xml = `data:application/xml,<?xml version="1.0" encoding="UTF-8"?>${encodeURIComponent(person.outerHTML)}`;
         
        //  console.log(person, xml);



         let xml = `data:application/xml,${data.data}`;
          let link = document.createElement("a");
          link.setAttribute("href", xml);
          link.setAttribute("download", Date.now()+"");
          link.click();
          // commit("SET_AUTH_ACCOUNT", data.data.results);
         
        })
    } catch (e) {
      const err = e.response.data.message
      console.log("ошибка - ",e)
      if (err === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getResultsXmlDb', {id})
      }else {
        dispatch('setMessageError', e)
      }
    }
  },
};

//Функция сохранения строки (данных) в файл на ПК из браузера
function saveToPC(str){
  let blob = new Blob([str], {type: "text/plain"});
  let link = document.createElement("a");
  link.setAttribute("href", URL.createObjectURL(blob));
  link.setAttribute("download", Date.now()+"");
  link.click();
}


const getters = {
  getAuthAccountResult(state) {
    console.log(state.result)
    return state.result
  },
 
}

const mutations = {
  [SET_AUTH_ACCOUNT] (state, result) {
    console.log("SET_AUTH_ACCOUNT", result)
    state.result = result
  },
  
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}