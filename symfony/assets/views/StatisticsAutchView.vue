<template>
   <Loader
    v-if="isLoading"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <h1>Результат прохождения тестов:</h1>  
    </div>
    <div class="container">
      <div class="row">
        <div v-for="(result, index ) in getAuthAccountResult" 
          :key="result.score + '_' + index"
        >
          <div class="col-sm-12 col-md-12 col-lg-12"> 
            <div class="card flex-shrink-1 shadow">
              <i class="result"
                :class="{resultTrue: result.score > 0 }"
              ><b>{{ index+1 }})</b> 
              {{ result.ticket ? `Билет № ${result.ticket.title}`: "Случайный набор" }}
              
              </i>
            </div>       
          </div>
        </div>

        

      </div>
  </div>
</div>
</template>
 
<script>
  import ResultTestQuestion from '../components/ResultQuestion/ResultTestQuestion.vue'
  import Loader from '../components/ui/Loader.vue'
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      ResultTestQuestion
    },
    data() {
      return {
        isLoading: true
      }
    },
    computed: {
      ...mapGetters(["getAuthAccountResult","getAutchUserToken"]),
    },
   
    methods: { 
      ...mapActions(["getAuthAccountDb", "getAuthAccountResultsDb"]),
      ...mapMutations([])
    },
      async mounted(){
      
     },
     async created(){
      await this.getAuthAccountResultsDb()
      console.log(this.getAuthAccountResult)
      this.isLoading = false
     }
 } 
 
</script>
<style lang="scss" scoped>
  .block{
    background-color: beige;
  }
  .title{
    margin-left: 10px;
  }
   .result::before{
    content: "X  ";
    font-family: Geneva, Arial;
    color:rgb(235, 25, 25);
    font-weight: 900;
    font-style:  oblique ;
  }
  .resultTrue::before{
    content: "V  ";
    color:rgb(22, 204, 104);
    font-style:  normal ;
  }
 @media (min-width: 1024px) {
  
 }
</style>