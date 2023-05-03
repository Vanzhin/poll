import { 
  SET_CRUMBS,
  SET_CRUMBS_HOME,
  SET_CRUMBS_LENGTH,
} from './mutation-types.js'

const state = () => ({
  crumbs: localStorage.getItem('crumbs') ?
  JSON.parse(localStorage.getItem('crumbs')):[],
})

const actions = {
 
  setCrumbs({ commit }, {crumbs}){
    commit("SET_CRUMBS", crumbs)
  },
  setCrumbsHome({ commit }, ){
    commit("SET_CRUMBS_HOME")
  },
  setCrumbsLength({ commit }, {len}){
    commit("SET_CRUMBS_LENGTH", len)
  }
}

const getters = {
  getCrumbs(state) {
    return state.crumbs
  },
  getCrumbsLength(state) {
    return state.crumbs.length
  },
}
const mutations = {
  [SET_CRUMBS] (state, crumbs) {
    console.log(crumbs)
    // state.crumbs.push(crumbs)
    state.crumbs = [...state.crumbs, ...crumbs]
    const parsed = JSON.stringify(state.crumbs)
    localStorage.setItem('crumbs', parsed)
    console.log(state.crumbs)
  },
  [SET_CRUMBS_HOME] (state) {
    state.crumbs=[{
      name:'home',
      title: "Разделы",
      iter: 1 
    }]
    const parsed = JSON.stringify(state.crumbs)
    localStorage.setItem('crumbs', parsed)
    console.log(state.crumbs)
  },
  [SET_CRUMBS_LENGTH](state, len) {
    state.crumbs.length = len
  }

 
}
export default {
    namespaced: false,
    state,
    actions,
    getters,
    mutations
  }