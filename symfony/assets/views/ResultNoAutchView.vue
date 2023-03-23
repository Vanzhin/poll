<template>
 <div class="container">
    <div class="row">
      <div v-for="(question, index ) in getResultQuestions" 
        :key="question.id"
      >
        <ResultTestQuestion
          :question="question"
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
</template>

<script>

import ResultTestQuestion from '../components/ResultQuestion/ResultTestQuestion.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  components: {
    ResultTestQuestion,
  },
  data() {
    return {
       
    }
  },
  computed:{
    ...mapGetters([
      "getResultQuestions" 
    ]),
    
  },
  methods: {
  
  },
  mounted(){
    window.scroll(0, 0);
  }
  
} 

</script>
<style lang="scss" scoped>
  
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
