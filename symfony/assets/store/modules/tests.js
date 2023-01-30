import { SET_QUESTION, SET_TEST_TITLE } from './mutation-types.js'


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
  testsTitle:'q',
  
 

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
  }
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
    return state.testsTitle 
  }
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
    return state.testsTitle = state.tests.find(test => {
      return +test.id === +id}) ;
  },
}


export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}