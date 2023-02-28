import { 
  SET_QUESTION, 
  SET_TEST_TITLE, 
  SET_TEST 
} from './mutation-types.js'
import axios from 'axios';

const state = () => ({
  tests: [
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
  test: null,
 

})

const actions = {
  getCources({ commit }) {
    axios
      .get("/api/cources")
      .then((res) => {
        commit("GET_COURCES", res.data.raw);
      })
      .catch((err) => {
        console.log(err);
      });
  },
  getQuestion(){},
  setTestTitle ({dispatch, commit}, {id}) {
    commit("SET_TEST_TITLE", id );
  },
  async importFileTestDb({ dispatch, commit, state }, {token, testFile} ){
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
        url: '/api/test/import_with_file',
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
          // dispatch('setMessage', {err: false, mes: data.message})
          // commit("SET_LOADER_TOGGLE")
        })
    } catch (e) {
      console.log("importFileTestDb err- ", e);
      // dispatch('setMessage', {err: true, 
      //   mes: `${e.response.data.message}  ${e.response.data.error[0]}`
      // })
    }
  },
  setTest({dispatch ,commit}, test) {
    dispatch("setTickets", test.ticket)
    commit("SET_TEST", test)
  },
};

const getters = {
  getTests(state) {
    return state.tests
  },
  getTestTitle:(state)=>(id) =>{
    return state.tests.find(test => {
      return +test.id === +id}) 
  },
  getTestTitleActive:(state)=>{
    console.log(state.test)
    return state.test.title 
  },
  getTest(state) {
    return state.test
  },
}

const mutations = {
  GET_COURCES(state, cources) {
    return state.cources = {
      active: 0, list: cources, isLoaded: true
    }
  },
  [SET_QUESTION] (state, id) {
    if (state.cources.list.hasOwnProperty(id)) {
      return state.cources.active = id;
    }
    return;
  },
  [SET_TEST_TITLE] (state, id) {
    return state.testTitle = state.tests.find(test => {
      return +test.id === +id}) ;
  },
  [SET_TEST] (state, test){
    return state.test = test
  },
}


export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}