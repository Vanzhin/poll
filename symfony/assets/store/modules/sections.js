import { SET_QUESTION } from './mutation-types.js'


const state = () => ({
  sections: [
    { 
      id: 1,
      title: "Промбезопасноть",
      type_responses : "one",
      image: '_prom.jpg',
      information: ""
    },
    { 
      id: 2,
      title: "Электробезопасность",
      type_responses : "one",
      image: '_electro5.jpg',
      information: ""
    },
    { 
      id: 3,
      title: "Пожарная безопасность",
      type_responses : "one",
      image: '_pb.jpg',
      information: ""
    },
    { 
      id: 4,
      title: "Энергетическая безопасность",
      type_responses : "one",
      image: '_energo1.jpg',
      information: ""
    },
    { 
      id: 5,
      title: "Аттестация сварщиков (НАКС)",
      type_responses : "one",
      image: '_naks.jpg',
      information: ""
    },
    { 
      id: 6,
      title: "Охрана труда в организациях",
      type_responses : "one",
      image: '_ot.jpg',
      information: ""
    },
  ],
  question:{},
  
  
})

const actions = {
  getCour({ commit }) {
    axios
      .get("/api/cources")
      .then((res) => {
        commit("GET_COURCES", res.data.raw);
      })
      .catch((err) => {
        console.log(err);
      });
  },
  getQues(){}
};

const getters = {
  getSections(state) {
    return state.sections 
  },
  getSectionTitle:(state)=>(id) =>{
    console.log(id)
    return state.sections.find(section => {
      console.log(+section.id === +id)
      return +section.id === +id}) 
  }
}

const mutations = {
 
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}