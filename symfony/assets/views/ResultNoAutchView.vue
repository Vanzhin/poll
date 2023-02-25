<template>
  <Loader
    v-if="getIsLoaderQuestions"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <h1>Результат:</h1>  
      <h2> {{ getTestTitleActive.title }}</h2>
      <div class="test">
        <p>Билет №: {{  }}</p>  
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div v-for="(qestion, index ) in getResultQuestions" 
          :key="qestion.id"
        >
          <ResultTestQuestion
            :qestion="qestion"
            :index="index"
          /> 
        </div>

        <div class="col-sm-12 col-md-12 col-lg-12"> 
          <div class="card flex-shrink-1 shadow">
            <h5>Для просмотра более подробной информации </h5>
            <div class="links">
              <RouterLink :to="{ name: 'logoutlink'}" class="routerLink"> 
                <h5>   /авторизуйтесь/  </h5>
              </RouterLink>
            </div>
          </div>       
        </div>

      </div>
  </div>
</div>
</template>

<script>

import ResultTestQuestion from '../components/ResultTestQuestion.vue'
import Loader from '../components/ui/Loader.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  components: {
    ResultTestQuestion,
    Loader
  },
  data() {
    return {
       
    }
  },
  computed:{
    ...mapGetters(["getIsLoaderQuestions", "getTestTitleActive","getResultQuestions" ]),
    
  },
  methods: {
  
  },
  mounted(){
    window.scroll(0, 0);
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
  [class*="col-"] {
  padding-top: 7px;
  padding-right: 7px;
  padding-left: 7px;
  margin-bottom: 10px;
  }
  .card{
    display: flex;
    padding: 15px;
    justify-content: center;
    text-align: center;
    & p{
      margin: 0;
    }
  }
  .button{
    border: 1px solid rgb(171 171 171);
    padding: 5px 10px;
    border-radius: 5px;
    &:hover{
      background-color: rgb(225 225 221);
    }
  }
  .links{
  & h5 {
    color:#2c6666;
  }
  :hover{
  cursor: pointer;
  h5 {color: #82a5a5;}
}}

@media (min-width: 1024px) {
 
}
</style>
