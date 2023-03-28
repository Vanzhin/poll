<template>
  <div class="block">
    <div class="title">
      <h1>Результат:</h1>  
      <h2> {{ getTestTitleActive }}</h2>
      <div class="test">
        <p > {{ getTicketTitle  }}</p>  
      </div>
    </div>
  
  
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
  </div>
</template>
<script>

import ResultAutchView from './ResultAutchView.vue'
import ResultNoAutchView from './ResultNoAutchView.vue'
import Loader from '../components/ui/LoaderView.vue'
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
    ...mapGetters([
      "getIsAutchUser", 
      "getAutchUserToken", 
      "getTicketTitle",
      "getTestTitleActive",
    ]),
  },
  methods: {
    ...mapActions(["getQuestionsDb", "setResultDb", "getAuthRefresh"]),
  },
  async mounted(){
    console.log('монтирую результат')
    
  },
  async created(){
    console.log('создаю результат')
    this.loader = true
    window.scroll(0, 0);
    await this.setResultDb( {token: this.getAutchUserToken, userAuth:this.getIsAutchUser})
    
    this.loader = false
  },
  unmounted(){
    console.log('размонтирую результат!')
  }

} 

</script>
<style lang="scss" scoped>
    .block{
    background-color: rgb(207 207 199);
    padding: 10px ;
    
  }
  .title{
    margin: 10px;
    & h2{
      font-size: 1.4rem;
      color: #697a3f;
    }
  }
  .test{
    display: flex;
  }
</style>
