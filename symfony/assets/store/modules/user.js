import { 
  SET_QUESTION,
} from './mutation-types.js'


const state = () => ({
 
  question:{},
  
  
})

const actions = {
  async getLogInUser({ commit }) {
  try{
      await axios.get("/api/cources",{

      })
  } catch (e) {
    console.log(e);
    console.log("ошибка - ",e.response.data.message)
    dispatch(setErrorAction(e.response.data.message))
    return true
  }
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
  [SET_QUESTION] (state, id) {
    if (state.cources.list.hasOwnProperty(id)) {
      return state.cources.active = id;
    }
    return;
  },
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}