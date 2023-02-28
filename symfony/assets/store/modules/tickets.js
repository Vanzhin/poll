import { 
  SET_QUESTION,
  SET_TICKETS
 } from './mutation-types.js'
 import axios from 'axios';

const state = () => ({
  tickets: [
    { 
      id: 1,
      title: "dfgdfgdfg",
    },
    { 
      id: 2,
      title: "nfffffff",
    },
    { 
      id: 3,
      title: "ertert",
    },
    { 
      id: 4,
      title: "ertert",
    },
    { 
      id: 5,
      title: "ertert",
    },
    { 
      id: 6,
      title: "ertert",
    },
    { 
      id: 7,
      title: "ertert",
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
  getQuestion(){},
  setTickets ({dispatch, commit}, tickets) {
    commit("SET_TICKETS", tickets)
  }
};

const getters = {
  getTickets(state) {
    return state.tickets
  },
  
}

const mutations = {
  GET_COURCES(state, cources) {
    state.cources = {
      active: 0, list: cources, isLoaded: true
    }
  },
  [SET_QUESTION] (state, id) {
    if (state.cources.list.hasOwnProperty(id)) {
      state.cources.active = id;
    }
  },
  [SET_TICKETS] (state, tickets){
     state.tickets = tickets
  },
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}