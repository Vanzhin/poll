import { 
  SET_LOADER_TOGGLE,
  SET_LOADER_STATUS,
} from './mutation-types.js'

const state = () => ({
  metrikScr: `<script type="text/javascript" >
  (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
  m[i].l=1*new Date();
  for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
  k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
  (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

  ym(93347925, "init", {
       clickmap:true,
       trackLinks:true,
       accurateTrackBounce:true,
       webvisor:true
  });
</script>`,
  metrikNoscr:`
  <noscript><div><img src="https://mc.yandex.ru/watch/93347925" style="position:absolute; left:-9999px;" alt="" /></div></noscript>`
})

const actions = {
//   setIsLoaderToggle({ commit }){
//     commit("SET_LOADER_TOGGLE")
//   },
//   setIsLoaderStatus({ commit }, {status}){
//     commit("SET_LOADER_STATUS", status)
//   },
}

const getters = {
  getMetrikScr(state) {
    return state.metrikScr 
  },
  getMetrikNoscr(state) {
    return state.metrikNoscr 
  },
}
const mutations = {
  // [SET_LOADER_TOGGLE] (state, ) {
  //   // console.log("SET_LOADER_TOGGLE", state.isLoader)
  //   state.isLoader = !state.isLoader
  // },
  // [SET_LOADER_STATUS] (state, status) {
  //   // console.log("SET_LOADER_STATUS", state.isLoader)
  //   state.isLoader = status
  // },
}
export default {
    namespaced: false,
    state,
    actions,
    getters,
    mutations
  }