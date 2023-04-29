import { 
  SET_QUESTIONS, 
   
  
  SET_QUESTION,
  SET_QUESTIONS_IMPORT_ERROR,
  SET_QUESTIONS_TICKET,
  SET_QUESTIONS_SECTION
} from './mutation-types.js'
import axios from 'axios';

const state = () => ({
  questionsTicket: [],
  questionsSection: [],
  question:null,
  questions:[],
  questionsDb:[],
  
  
  resultTicketUser:localStorage.getItem('resultTicketUser') ?
    JSON.parse(localStorage.getItem('resultTicketUser')): [],
  questionsImportError: null
  
})

const actions = {
  //запрос на получение вопросов пользователем при тестированиии
  async getQuestionsDb({dispatch, commit }, {id, slug}) {
    //  const slag = 'mindal-kraiola-ooo-kompaniia-rybvektorzheldorprof' // опен серв
    // const slag = 'korichnyi-ooo-kompaniia-bashkirorion'// докер
   
    let url = ''
    if (id === "rnd20" || id === "rnd20t") {
      url = `/api/test/${slug}/question/20`
    } else if (id === "rnd"){
      const i = Math.floor(Math.random() * (30 - 1) )
     
      url = `/api/test/${slug}/question/${i}`
    } else if (id === "rndmax"){
      const i = await dispatch("getCountQuestionsTest")
    
      url = `/api/test/${slug}/question/${i}`
    } else {
      url = `/api/ticket/${id}/question`
    }
    //getCountQuestionsTest
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
          commit("SET_QUESTIONS", data.questions);
        })
    } catch (e) {
      dispatch('setMessageError', e)
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
          commit("SET_QUESTIONS_TICKET", data.questions);
        })
    } catch (e) {
      dispatch('setMessageError', e)
    }
  },
  //запрос для админки на получение вопросов секции по ее id
  async getQuestionsSectionIdDb({dispatch, commit }, {id,  limit=10000}) {
    const token = await dispatch("getAutchUserTokenAction")
    try{
      const config = {
        method: 'get',
        url: `/api/admin/question/section/${id}?limit=${limit}`,
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        }
      };
      
      await axios(config)
        .then(({data})=>{
          commit("SET_QUESTIONS_SECTION", data.question);
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getQuestionsSectionIdDb', {id})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  //запрос на получение вопросов теста по его id для админки
  async getQuestionsTestIdDb({dispatch, commit }, {id, page=null, limit=10}) {
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
      
      await axios(config)
        .then(({data})=>{
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
  //сохранение нового вопроса в базу. если передается id - вносятся изменения
  async saveQuestionDb({ dispatch, commit, state }, {questionSend, id = null} ){

    // dispatch("setIsLoaderStatus", {status: true})
    const token = await dispatch("getAutchUserTokenAction")
    try{
      // const data = new FormData(questionSend);
      for(let [name, value] of questionSend) {
        // console.dir(`${name} = ${value}`)
      }
      const config = {
        method: 'post',
        url: '/api/admin/question/create_with_variant',
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        },
        data:  questionSend
      };
      if (id) {
        config.url = `/api/admin/question/${id}/edit_with_variant`
      }
      await axios(config)
        .then(({data})=>{
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
  //утверждение вопроса
  async approveQuestionDb({ dispatch, commit, state }, {questionSend} ){
    const token = await dispatch("getAutchUserTokenAction")
    try{
      const config = {
        method: 'post',
        url: `/api/admin/question/publish`,
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`
        },
        data:  JSON.stringify({"questionIds": questionSend})
      };
      await axios(config)
        .then(({data})=>{
          dispatch('setMessage', data)
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('approveQuestionDb', {questionSend})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  //утверждение или скрытие всех вопросов теста по его id
  async approveQuestionsAllDb({ dispatch, commit, state }, {id, param} ){
    
    const token = await dispatch("getAutchUserTokenAction")
    try{
      const config = {
        method: 'get',
        url: `/api/admin/question/publish/test/${id}?publish=${param}`,
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        }
      };
      await axios(config)
        .then(({data})=>{
          dispatch('setMessage', data)
          
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('approveQuestionsAllDb',{id, param})
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
      const config = {
        method: 'post',
        url: `/api/admin/test/${id}/upload`,
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        },
        data:  data
      };
      await axios(config)
        .then(({data})=>{
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
  //удаление вопроса из БД
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
  
  setQuestionsImportError({ commit }, error){
    commit("SET_QUESTIONS_IMPORT_ERROR", error);
  }
};




const getters = {
  getQuestions(state) {
    return state.questions 
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
  getQuestionsSection(state) {
    return state.questionsSection 
  },
}

const mutations = {
  [SET_QUESTIONS] (state, questions ) {
    state.questions = questions
  },
  [SET_QUESTION] (state, question ) {
    state.question = question
  },
  [SET_QUESTIONS_IMPORT_ERROR](state, error ) {
    state.questionsImportError = error
  },
  [SET_QUESTIONS_TICKET](state, questions ) {
    state.questionsTicket = questions
  },
  [SET_QUESTIONS_SECTION](state, questions ) {
    state.questionsSection = questions
  },
}

export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}