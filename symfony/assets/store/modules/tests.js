import { 
  SET_TEST_TITLE, 
  SET_TEST,
  SET_TESTS
} from './mutation-types.js'
import axios from 'axios';

const state = () => ({
  testsTest: [
    { 
      id: 1,
      title: "ЭБ 1344.2. Проверка знаний электротехнического персонала организаций, осуществляющего эксплуатацию оборудования кабельных линий электросетевого хозяйства потребителей (III группа по электробезопасности до 1000 В)",
      type_responses : "one",
      image: null
    },
    { 
      id: 2,
      title: "ЭБ 1345.2. Проверка знаний электротехнического персонала организаций, осуществляющего эксплуатацию оборудования кабельных линий электросетевого хозяйства потребителей (III группа по электробезопасности выше 1000 В)",
      type_responses : "one",
      image: null
    },
    { 
      id: 3,
      title: "ЭБ 1346.2. Проверка знаний электротехнического персонала организаций, осуществляющего эксплуатацию оборудования кабельных линий электросетевого хозяйства потребителей (IV группа по электробезопасности до 1000 В)",
      type_responses : "one",
      image: null
    },
    { 
      id: 4,
      title: "ЭБ 1347.2. Проверка знаний электротехнического персонала организаций, осуществляющего эксплуатацию оборудования кабельных линий электросетевого хозяйства потребителей (IV группа по электробезопасности выше 1000 В)",
      type_responses : "one",
      image: null
    },
    { 
      id: 5,
      title: "ЭБ 1348.2. Проверка знаний электротехнического персонала организаций, осуществляющего эксплуатацию оборудования кабельных линий электросетевого хозяйства потребителей (V группа по электробезопасности)",
      type_responses : "one",
      image: null
    },
    { 
      id: 6,
      title:  "Проверка знаний норм и правил в области энергетического надзора",
      type_responses : "one",
      image: null
    },
  ],
  tests: null,
  test: null,
 

})

const actions = {
  async getTestsDB({dispatch ,commit}, {page = null, token = null}){
    const config = {
      method: 'get',
      url: `/api/admin/test`,
      headers: { 
        Accept: 'application/json', 
        // Authorization: `Bearer ${token}`
      }
    }

    if (page) {
      config.url = config.url + `?page=${page}`
    }
    try{
      await axios(config)
        .then(({data})=>{
          console.log("getTestsDb - ",  data)
          dispatch("setTests", data.test)
          dispatch("setPagination", data.pagination);
        })
    } catch (e) {
      console.log("Ошибка при получении теста", e)
      dispatch('setMessage', {err: true, 
        mes: `${e.response.data.message}!<hr> 
        ${e.response.data.error[0]}.`
      })
    }
  },
  setTestTitle ({dispatch, commit}, {title}) {
    commit("SET_TEST_TITLE", title );
  },
  async importFileTestDb({ dispatch, commit, state }, {id, token, testFile} ){
    console.dir(testFile)
    commit("SET_LOADER_TOGGLE")
    try{
      // Array.from(questionSend).filter(inp => inp.name !== "")
      const data = new FormData(testFile);
      for(let [name, value] of data) {
        console.dir(`${name} = ${value}`); // key1=value1, потом key2=value2
      }
      const config = {
        method: 'post',
        url: `/api/admin/test/${id}/upload`,
        headers: { 
          Accept: 'application/json', 
          // Authorization: `Bearer ${token}`
        },
        data:  data
      };
      console.log("importFileTestDb - ",  config)
      await axios(config)
        .then(({data})=>{
          console.log("importFileTestDb - ",  data)
          dispatch('setMessage', {err: false, mes: data.message})
          // commit("SET_LOADER_TOGGLE")
        })
    } catch (e) {
      console.log("importFileTestDb err- ", e);
      dispatch('setMessage', {err: true, 
         mes: `${e.response.data.message}!<hr>  ${e.response.data.error[0]}`
      })
    }
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
  async getTestIdDb({dispatch ,commit}, {id}){
    const config = {
      method: 'get',
      url: `/api/admin/test/${id}`,
      headers: { 
        Accept: 'application/json', 
        // Authorization: `Bearer ${token}`
      }
    }
    try{
      await axios(config)
        .then(({data})=>{
          console.log("getTestDb - ",  data)
          dispatch("setTest", data)
          
        })
    } catch (e) {
      console.log("Ошибка при получении теста", e)
      dispatch('setMessage', {err: true, 
        mes: `${e.response.data.message}!  
        ${e.response.data.error[0]}.`
      })
    }
  },
  async deleteTestDb({dispatch, commit}, {id, parentId, token, page, type}){
    const config = {
      method: 'get',
      url: `/api/admin/test/${id}/delete`,
      headers: { 
        Accept: 'application/json', 
        // Authorization: `Bearer ${token}`
      }
    };
    try{
      await axios(config)
        .then(({data})=>{
          console.log("deleteTestDb - удалено",  data)
          if (type === "test") {
            dispatch("getTestsDB",  { page })
          } else {
            if ( type === "test") {
              dispatch("getTestsDB",  { page })
              
            } else {
            dispatch("getCategorysDB",  { page: null , parentId})}}
            dispatch('setMessage', {err: false, mes: data.message})
          })
    } catch (e) {
      console.log("Ошибка при удалении", e);
      dispatch('setMessage', {err: true, 
        mes: `${e.response.data.message}!  
        ${e.response.data.error[0]}.`
      })
    }
  },
  async createTest ({dispatch, commit}, {questionSend, token}){
    const data = new FormData(questionSend);
    for(let [name, value] of data) {
      console.dir(`${name} = ${value}`); // key1=value1, потом key2=value2
    }
    const config = {
      method: 'post',
      url: `/api/admin/test/create`,
      headers: { 
        Accept: 'application/json', 
        // Authorization: `Bearer ${token}`
      },
      data: data
    };
    try{
      await axios(config)
        .then(({data})=>{
          console.log("createTest - создано",  data)
          dispatch('setMessage', {err: false, mes: data.message})
          // dispatch("getCategorysDB",  { page: null , parentId: id });
        })
    } catch (e) {
      console.log("Ошибка при создании",e);
      dispatch('setMessage', {err: true, 
        mes: `${e.response.data.message}!  
        ${e.response.data.error[0]}.`
      })
    }
  },
  async editTest ({dispatch, commit}, {id, questionSend, token}){
    const data = new FormData(questionSend);
    for(let [name, value] of data) {
      console.dir(`${name} = ${value}`); // key1=value1, потом key2=value2
    }
    const config = {
      method: 'post',
      url: `/api/admin/test/${id}/edit`,
      headers: { 
        Accept: 'application/json', 
        // Authorization: `Bearer ${token}`
      },
      data: data
    };
    try{
      await axios(config)
        .then(({data})=>{
          console.log("editTest - изменено",  data)
          dispatch('setMessage', {err: false, mes: data.message})
          // dispatch("getCategorysDB", { page: null , parentId: id });
        })
    } catch (e) {
      console.log("Ошибка при изменении:", e);
      dispatch('setMessage', {err: true, 
        mes: `${e.response.data.message}!  
        ${e.response.data.error[0]}.`
      })
    }
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
    console.log(state.test)
    return state.test ? state.test.title : ''
  },
  getTest(state) {
    return state.test
  },
  getSlug(state) {
    return state.test.slug
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
    return state.test = test
  },
  [SET_TESTS] (state, tests){
    return state.tests = tests
  },
}


export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}