<template>
  <ResultAutchView
    v-if="getIsAutchUser"
  />
  <ResultNoAutchView
    v-else
  />
   
</template>

<script>

import ResultAutchView from './ResultAutchView.vue'
import ResultNoAutchView from './ResultNoAutchView.vue'
import Loader from '../components/ui/Loader.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  components: {
    ResultAutchView,
    ResultNoAutchView
  },
  data() {
    return {
    }
  },
  computed:{
    ...mapGetters(["getIsAutchUser", "getAutchUserToken"]),
  },
  methods: {
    ...mapActions(["getQuestionsDb", "setResultDb"]),
  },
  async mounted(){
    window.scroll(0, 0);
    await this.setResultDb( {token: this.getAutchUserToken, userAuth:this.getIsAutchUser})
  }
  
} 

</script>
<style lang="scss" scoped>

</style>
