import { } from './mutation-types.js'


const state = () => ({
  areas: [
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
  area:{},
  
  
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
  getQuestion(){}
};

const getters = {
  getAreas(state) {
    return state.areas 
  },
  getAreasOne(state) {
    return state.areas.filter(area => {return area.type_responses === "one"}) 
  },
  getAreaTitle:(state)=>(id) =>{
    console.log(id)
    return state.areas.find(area => {
      console.log(+area.id === +id)
      return +area.id === +id}) 
  }
}

const mutations = {
  GET_COURCES(state, cources) {
    state.cources = {
      active: 0, list: cources, isLoaded: true
    }
  },
  
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}