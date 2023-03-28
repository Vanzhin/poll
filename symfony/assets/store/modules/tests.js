import { 
  SET_TEST_TITLE, 
  SET_TEST,
  SET_TESTS
} from './mutation-types.js'
import axios from 'axios';

const state = () => ({
  testsTest: [],
  tests: localStorage.getItem('tests') ?
  JSON.parse(localStorage.getItem('tests')) : null,
  test: localStorage.getItem('test') ?
  JSON.parse(localStorage.getItem('test')) : null,
 

})

const actions = {
  //запрос на получение всех тестов 
  async getTestsDB({dispatch ,commit}, {page = null, limit = 10, limitMax = false}){
    const token = await dispatch("getAutchUserTokenAction")
    const config = {
      method: 'get',
      url: `/api/admin/test?limit=${limit}`,
      headers: { 
        Accept: 'application/json', 
        Authorization: `Bearer ${token}`
      }
    }
    if (page) {
      config.url = config.url + `&page=${page}`
    }
    try{
      await axios(config)
        .then(({data})=>{
          console.log("getTestsDb - ",  data)
          dispatch("setTests", data.test)
          dispatch("setPagination", data.pagination);

        })
      if (limitMax) {
        const limit = await dispatch('getTotalItemAction')
        await dispatch('getTestsDB', { limit })
      }
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getTestsDB', {page, limit, limitMax})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  setTestTitle ({dispatch, commit}, {title}) {
    commit("SET_TEST_TITLE", title );
  },
  setTest({dispatch ,commit}, test) {
    if (test) dispatch("setTickets", test.ticket)
    commit("SET_TEST", test)
  },
  setTestItem({dispatch ,commit}, test) {
    commit("SET_TEST", test)
  },
  setTests({dispatch ,commit}, tests) {
    commit("SET_TESTS", tests)
  },
  selectTestId({dispatch ,commit, state}, {id}) {
    console.log("id - ",  id)
    console.log("выбранный тест - ",  state)
    const test = state.tests.find(test => test.id === +id)
    console.log("выбранный тест - ",  test)
    dispatch("setTest", test)
  },
  //получение информации теста по его id
  async getTestIdDb({dispatch ,commit}, {id}){
    const token = await dispatch("getAutchUserTokenAction")
    const config = {
      method: 'get',
      url: `/api/admin/test/${id}`,
      headers: { 
        Accept: 'application/json', 
        Authorization: `Bearer ${token}`
      }
    }
    console.log(config)
    try{
      await axios(config)
        .then(({data})=>{
          console.log(data)
          commit("SET_TEST", data)
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getTestIdDb', {id})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  async deleteTestDb({dispatch, commit}, {id, parentId, page, type}){
    const token = await dispatch("getAutchUserTokenAction")
    const config = {
      method: 'get',
      url: `/api/admin/test/${id}/delete`,
      headers: { 
        Accept: 'application/json', 
        Authorization: `Bearer ${token}`
      }
    };
    try{
      await axios(config)
        .then(({data})=>{
          console.log("deleteTestDb - удалено",  data)
          if ( type === "test") {
            dispatch("getTestsDB",  { page })
          } else {
            dispatch("getCategorysDB",  { page: null , parentId})
          }
          dispatch('setMessage', data)
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('deleteTestDb', {id, parentId, page, type})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  async createTest ({dispatch, commit}, {questionSend}){
    const token = await dispatch("getAutchUserTokenAction")
    const data = new FormData(questionSend);
    for(let [name, value] of data) {
      console.dir(`${name} = ${value}`)
    }
    const config = {
      method: 'post',
      url: `/api/admin/test/create`,
      headers: { 
        Accept: 'application/json', 
        Authorization: `Bearer ${token}`
      },
      data: data
    };
    try{
      await axios(config)
        .then(({data})=>{
          console.log("createTest - создано",  data)
          dispatch('setMessage', data)
          // dispatch("getCategorysDB",  { page: null , parentId: id });
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('createTest', {questionSend})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  async editTest ({dispatch, commit}, {id, questionSend}){
    const token = await dispatch("getAutchUserTokenAction")
    const data = new FormData(questionSend);
    for(let [name, value] of data) {
      console.dir(`${name} = ${value}`); 
    }
    const config = {
      method: 'post',
      url: `/api/admin/test/${id}/edit`,
      headers: { 
        Accept: 'application/json', 
        Authorization: `Bearer ${token}`
      },
      data: data
    };
    try{
      await axios(config)
        .then(({data})=>{
          console.log("editTest - изменено",  data)
          dispatch('setMessage',  data)
          // dispatch("getCategorysDB", { page: null , parentId: id });
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('editTest', {id, questionSend})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  getCountQuestionsTest({state}) {
    return state.test.questionCount ?? 1
  },
};

const getters = {
  getTests(state) {
    return state.tests
  },
  getTestTitle(state) {
    return state.test.title
  },
  getTestTitleActive(state) {
    return state.test ? state.test.title : ''
  },
  getTest(state) {
    return state.test
  },
  getSlug(state) {
    return state.test.slug
  },
  getTestId(state) {
    return state.test.id
  },
}

const mutations = {
  GET_COURCES(state, cources) {
    return state.cources = {
      active: 0, list: cources, isLoaded: true
    }
  },
  [SET_TEST_TITLE] (state, id) {
    return state.testTitle = state.tests.find(test => {
      return +test.id === +id}) ;
  },
  [SET_TEST] (state, test){
    console.log(test)
    const parsed = JSON.stringify(
      test
    );
    localStorage.setItem('test', parsed);
    state.test = test
  },
  [SET_TESTS] (state, tests){
    if (tests) { 
      const parsed = JSON.stringify(tests)
      localStorage.setItem('tests', parsed);
    } else { localStorage.removeItem('tests')}
        state.tests = tests
  },
}


export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}