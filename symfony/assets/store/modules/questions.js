import { 
  SET_QUESTIONS, 
  SET_QUESTIONS_RESULT, 
  SET_RESULT_TICKET_USER,
  SET_QUESTION,
  SET_QUESTIONS_IMPORT_ERROR,
  SET_QUESTIONS_TICKET
} from './mutation-types.js'
import axios from 'axios';

const state = () => ({
  questionsTicket: [],
  question:null,
  questions:[],
  questionsDb:[],
  resultQuestions:localStorage.getItem('resultQuestions') ?
    JSON.parse(localStorage.getItem('resultQuestions')): [],
  
  resultTicketUser:localStorage.getItem('resultTicketUser') ?
    JSON.parse(localStorage.getItem('resultTicketUser')): [],
  questionsImportError: null
  
})






const actions = {
  //запрос на получение вопросов пользователем при тестированиии
  async getQuestionsDb({dispatch, commit }, {id, slug}) {
    //  const slag = 'mindal-kraiola-ooo-kompaniia-rybvektorzheldorprof' // опен серв
    // const slag = 'korichnyi-ooo-kompaniia-bashkirorion'// докер
    console.log("id - ",  id)
    let url = ''
    if (id === "rnd20" || id === "rnd20t") {
      url = `/api/test/${slug}/question/20`
    } else if (id === "rnd"){
      const i = Math.floor(Math.random() * (30 - 1) )
      console.log("i - ",  i)
      url = `/api/test/${slug}/question/${i}`
    } else {
      url = `/api/ticket/${id}/question`
    }
    try{
      const config = {
        method: 'get',
        url: url,
        headers: { 
          Accept: 'application/json', 
          // Authorization: `Bearer ${token}`
        }
      };
      await axios(config)
        .then(({data})=>{
          console.log("getQuestionsDb - ",  data.questions)
          commit("SET_QUESTIONS", data.questions);
        })
    } catch (e) {
        console.log(e.message);
    }
  },
  //запрос для админки на получение вопросов билета по его id
  async getQuestionsTickeetIdDb({dispatch, commit }, {id}) {
    try{
      const config = {
        method: 'get',
        url: `/api/ticket/${id}/question`,
        headers: { 
          Accept: 'application/json', 
          // Authorization: `Bearer ${token}`
        }
      };
      await axios(config)
        .then(({data})=>{
          console.log("getQuestionsTickeetIdD - ",  data)
          commit("SET_QUESTIONS_TICKET", data.questions);
        })
    } catch (e) {
        console.log(e);
    }
  },
  //запрос на получение вопросов теста по его id для админки
  async getQuestionsTestIdDb({dispatch, commit }, {id, page=null, limit=10}) {
    console.log("id - ",  id)
    const token = await dispatch("getAutchUserTokenAction")
    try{
      const config = {
        method: 'get',
        url: `/api/admin/test/${id}/question?limit=${limit}`,
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        }
      };
      if (page) {config.url = config.url + `&page=${page}`}
      console.log(config)
      await axios(config)
        .then(({data})=>{
          console.log("getQuestionsTestIdDb - ",  data.question)
          commit("SET_QUESTIONS", data.question);
          dispatch("setPagination", data.pagination);
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getQuestionsTestIdDb', {id, page})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  //получение вороса по его id
  async getQuestionIdDb({dispatch, commit }, {id}) {
    const token = await dispatch("getAutchUserTokenAction")
    console.log("id - ",  id)
    try{
      const config = {
        method: 'get',
        url: `/api/admin/question/${id}`,
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        }
      };
      await axios(config)
        .then(({data})=>{
          console.log("getQuestionIdDb - ",  data)
          commit("SET_QUESTION", data);
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getQuestionIdDb', {id})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  // отаправка результата прохождения теста на сервер
  async setResultDb({dispatch, commit, state }, {userAuth} ){
    const token = await dispatch("getAutchUserTokenAction")
    console.log(JSON.stringify(state.resultTicketUser))
    
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
        console.log('авторизован')
        config.url = '/api/auth/test/handle'
        config.headers.Authorization = `Bearer ${token}`
      }
      console.log(config) 
      await axios(config)
        .then(({data})=>{
          console.log("setResultDb - ",  data)
          commit("SET_QUESTIONS_RESULT", data);
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

  //сохранение нового вопроса в базу
  async saveQuestionDb({ dispatch, commit, state }, {questionSend, id = null} ){
    console.dir(questionSend)
    dispatch("setIsLoaderStatus", {status: true})
    const token = await dispatch("getAutchUserTokenAction")
    try{
      const data = new FormData(questionSend);
      for(let [name, value] of data) {
        console.dir(`${name} = ${value}`)
      }
      const config = {
        method: 'post',
        url: '/api/admin/question/create_with_variant',
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        },
        data:  data
      };
      if (id) {
        config.url = `/api/admin/question/${id}/edit_with_variant`
      }
      console.log(config)
      await axios(config)
        .then(({data})=>{
          console.log("saveQuestionDb - ",  data)
          dispatch("setIsLoaderStatus", {status: false})
          dispatch('setMessage', data)
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('saveQuestionDb', {id, questionSend})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  //импорт вопросов из файла
  async importQuestionsFileDb({ dispatch, commit, state }, {id, testFile} ){
    commit("SET_QUESTIONS_IMPORT_ERROR", null)
    dispatch("setIsLoaderStatus", {status: true})
    const token = await dispatch("getAutchUserTokenAction")
    try{
      const data = new FormData(testFile);
      for(let [name, value] of data) {
        console.dir(`${name} = ${value}`); // key1=value1, потом key2=value2
      }
      const config = {
        method: 'post',
        url: `/api/admin/test/${id}/upload`,
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        },
        data:  data
      };
      console.log(config)
      await axios(config)
        .then(({data})=>{
          console.log("saveQuestionDb - ",  data)
          dispatch("setIsLoaderStatus", {status: false})
          dispatch('setMessage',  data)
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('importQuestionsFileDb', {id, testFile})
      } else {
        if (e.response.status === 422){
          // dispatch('setQuestionsImportError', e.response.data.error)
          commit("SET_QUESTIONS_IMPORT_ERROR", e.response.data.error)
        }
        dispatch('setMessageError', e)
        dispatch("setIsLoaderStatus", {status: false})
      }
    }
  },

  async deleteQuestionDb({ dispatch, commit, state }, { id, testId, page = null} ){
    const token = await dispatch("getAutchUserTokenAction")
    const config = {
        method: 'get',
        url: `/api/admin/question/${id}/delete`,
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        },
      };
      console.log(config)
    try{
      await axios(config)
        .then(({data})=>{
          dispatch('getQuestionsTestIdDb', {id: testId, page})
          dispatch('setMessage', data)
          
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('deleteQuestionDb', {id, testId, page })
      } else {
        dispatch('setMessageError', e)
      }
    }
  },

  
  setQuestion({ commit }, question){
    commit("SET_QUESTION", question)
  },
  saveResultTicketUser({ commit }, ticket){
    commit("SET_RESULT_TICKET_USER", ticket);
  },
  setQuestionsImportError({ commit }, error){
    commit("SET_QUESTIONS_IMPORT_ERROR", error);
  }
};




const getters = {
  getQuestions(state) {
    return state.questions 
  },
  getResultQuestions(state) {
    return state.resultQuestions 
  },
  getQuestion(state) {
    return state.question 
  },
  getQuestionsImportError(state) {
    return state.questionsImportError 
  },
  getQuestionsTicket(state) {
    return state.questionsTicket 
  },
}

const mutations = {
  [SET_QUESTIONS] (state, questions ) {
    console.log("SET_QUESTIONS", questions)
    state.questions = questions
  },
  [SET_QUESTIONS_RESULT] (state, questions) {
    console.log("SET_QUESTIONS_RESULT", questions)
    state.resultQuestions = questions
    // localStorage.setItem('resultQuestions', JSON.stringify(questions));
  },
  [SET_RESULT_TICKET_USER] (state, ticket) {
    console.log("SET_RESULT_TICKET_USER", )
    state.resultTicketUser = ticket
    localStorage.setItem('resultTicketUser', JSON.stringify(ticket));
  },
  [SET_QUESTION] (state, question ) {
    console.log("SET_QUESTION", question)
    state.question = question
  },
  [SET_QUESTIONS_IMPORT_ERROR](state, error ) {
    console.log("SET_QUESTIONS_IMPORT_ERROR", error)
    state.questionsImportError = error
  },
  [SET_QUESTIONS_TICKET](state, questions ) {
    console.log("SET_QUESTIONS_TICKET", questions)
    state.questionsTicket = questions
  },
}

export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}