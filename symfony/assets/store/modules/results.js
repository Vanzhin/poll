import { 
  SET_RESULT_QUESTIONS,
  SET_AUTCH_ACCOUNT,
  SET_RESULT_TICKET_USER,
  SET_RESULT_STATISTICS_QUESTIONS,
  SET_RESULT_ID,
  SET_FORM_INFO_VISIBLE,
  SET_FORM_INFO
} from './mutation-types.js'

import axios from 'axios';

const state = () => ({
 
  result: [],
  resultQuestions:localStorage.getItem('resultQuestions') ?
    JSON.parse(localStorage.getItem('resultQuestions')): [],
  formInfoVisible: false,
  formInfoParam: "",
  resultId: null,
  
  
})

const actions = {
  // отаправка результата прохождения теста на сервер
  async setResultDb({dispatch, commit, state }, {userAuth} ){
    const token = await dispatch("getAutchUserTokenAction")
    try{
      const config = {
        method: 'post',
        url: '/api/test/handle',
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json'
        },
        data:  JSON.stringify(state.resultTicketUser)
      };
      if (userAuth) {
       
        config.url = '/api/auth/test/handle'
        config.headers.Authorization = `Bearer ${token}`
      }
      
      await axios(config)
        .then(({data})=>{
          commit("SET_RESULT_QUESTIONS", data.question);
        })
        const err = {
          errPrizn: false
        }
       return err
    } catch (e) {
        if (e.response.data.message === "Expired JWT Token") {
          await dispatch('getAuthRefresh')
          await dispatch('setResultDb', {userAuth})
        } else {
          dispatch('setMessageError', e)
        }
        
    }
  },
  //получение вопросов результата по его id
  async getResultIdAnswersDb({dispatch, commit, state }, {id} ){
    const token = await dispatch("getAutchUserTokenAction")
    
    try{
      const config = {
        method: 'get',
        url: `/api/auth/result/${id}/answer`,
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
      };
      await axios(config)
        .then(({data})=>{
          commit("SET_RESULT_STATISTICS_QUESTIONS", data);
        })
        const err = {
          errPrizn: false
        }
       return err
    } catch (e) {
        if (e.response.data.message === "Expired JWT Token") {
          await dispatch('getAuthRefresh')
          await dispatch('getResultIdAnswersDb', {id})
        } else {
          dispatch('setMessageError', e)
        }
        
    }
  },
  saveResultTicketUser({ commit }, ticket){
    commit("SET_RESULT_TICKET_USER", ticket);
  },
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
          commit("SET_AUTCH_ACCOUNT", data.data.results);
        })
    } catch (e) {
      const err = e.response.data.message
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
          commit("SET_AUTCH_ACCOUNT", data.data.results);
          dispatch("setPagination", data.pagination);
        })
    } catch (e) {
      const err = e.response.data.message
      if (err === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getAuthAccountResultsDb')
      }else {
        dispatch('setMessageError', e)
      }
    }
  },
  // получение отчета в xml
  async getResultsXmlDb({dispatch, commit, state },{info} ) {
    const token = await dispatch("getAutchUserTokenAction")
    try {
      const config = {
        method: 'post',
        url: `/api/auth/result/${state.resultId}/report`,
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        data: info
      }
      await axios(config)
        .then((data)=>{
          // console.log("getResultsXmlDb - ",  data.data)
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
          // commit("SET_AUTCH_ACCOUNT", data.data.results);
         
        })
    } catch (e) {
      const err = e.response.data.message
      if (err === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getResultsXmlDb', {id})
      }else {
        dispatch('setMessageError', e)
      }
    }
  },
  changeFormInfoVisible({dispatch, commit, state },{param, visible}){
    commit("SET_FORM_INFO", {param, visible});
  },
  setResultId({dispatch, commit, state },{id}){
    commit("SET_RESULT_ID", id);
  }
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
    return state.result
  },
  getResultQuestions(state) {
    return state.resultQuestions 
  },
  getFormInfoVisible(state){
    return state.formInfoVisible
  },
  getFormInfoParam(state){
    return state.formInfoParam
  },
}

const mutations = {
  [SET_AUTCH_ACCOUNT] (state, result) {
   
    state.result = result
  },
  [SET_RESULT_QUESTIONS] (state, questions) {
   
    state.resultQuestions = questions
    // localStorage.setItem('resultQuestions', JSON.stringify(questions));
  },
  [SET_RESULT_TICKET_USER] (state, ticket) {
  
    state.resultTicketUser = ticket
    localStorage.setItem('resultTicketUser', JSON.stringify(ticket));
  },
  [SET_RESULT_STATISTICS_QUESTIONS] (state, questions) {
   
    state.resultQuestions = questions.map(question => {
      if (question.question){
        let questionItem = {...question.question}
        if (questionItem.type.title) {questionItem.type = questionItem.type.title}

        if (questionItem.type === 'input_one' ) {
          questionItem.result.true_answer = questionItem.variant[0].title
          if ( questionItem.variant[0].id === +questionItem.result.user_answer[0]){
            questionItem.result.user_answer[0] = questionItem.variant[0].title
          }
        } else {
          questionItem.result.true_answer = questionItem.result.true_answer.map(
            (item) =>{ return questionItem.variant.findIndex(variant => +variant.id === +item )}
          )
          questionItem.result.user_answer = questionItem.result.user_answer.map(
            (item) =>{ return questionItem.variant.findIndex(variant => +variant.id === +item )}
          )
        }
        return questionItem
      } else {
        return question
      }
    })
   
  },
  [SET_FORM_INFO] (state, {param, visible}) {
   
    state.formInfoVisible = visible
    state.formInfoParam = param
  },
  [SET_RESULT_ID] (state, id) {
   
    state.resultId = id
  },
  
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}