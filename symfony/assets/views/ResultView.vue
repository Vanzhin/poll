<template>
  <Loader
    v-if="loader"
  />
  <div
    v-else
  >
    <ResultAutchView
      v-if="getIsAutchUser"
    />
    <ResultNoAutchView
      v-else
    />
  </div>
   
   
</template>

<script>

import ResultAutchView from './ResultAutchView.vue'
import ResultNoAutchView from './ResultNoAutchView.vue'
import Loader from '../components/ui/Loader.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  components: {
    ResultAutchView,
    ResultNoAutchView,
    Loader
  },
  data() {
    return {
      loader: false
    }
  },
  computed:{
    ...mapGetters(["getIsAutchUser", "getAutchUserToken"]),
  },
  methods: {
    ...mapActions(["getQuestionsDb", "setResultDb", "getAuthRefresh"]),
  },
  async mounted(){
    this.loader = true
    window.scroll(0, 0);
    const res = await this.setResultDb( {token: this.getAutchUserToken, userAuth:this.getIsAutchUser})
    console.log(res.errPrizn)
    if (res.errPrizn) {
      if (res.mes === "Expired JWT Token"){
        await this.getAuthRefresh()
        await this.setResultDb( {token: this.getAutchUserToken, userAuth:this.getIsAutchUser})
      }
    }
    
    this.loader = false
  }
  
} 

</script>
<style lang="scss" scoped>

</style>
