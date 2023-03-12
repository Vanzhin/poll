import { SET_QUESTION } from './mutation-types.js'


const state = () => ({
  questions: [
    { 
      id: 1,
      title: "dfgdfgdfg",
      type_responses : "one",
      image: null
    },
    { 
      id: 2,
      title: "nfffffff",
      type_responses : "one",
      image: null
    },
    { 
      id: 3,
      title: "ertert",
      type_responses : "one",
      image: null
    },
  ],
  question:{},
  
  
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
  setQuestions(state) {
    return state.questions 
  },
  setQuestionsOne(state) {
    return state.questions.filter(question => {return question.type_responses === "one"}) 
  }
}

const mutations = {
  GET_COURCES(state, cources) {
    return state.cources = {
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