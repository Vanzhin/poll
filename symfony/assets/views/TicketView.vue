<template>
  <div class="block">
    <div class="title">
      <h2> {{ testName.title }}</h2>
      <div class="test">
        <p>Билет №: {{ $route.params.id }}</p>  
      </div>
      
    </div>
    <div class="container">
      <div class="row">
        <form @submit.prevent="onSubmit">
          <div v-for="(qestion, index ) in qestions" 
              :key="qestion.id">
            <TestQuestionRadio
              v-if="qestion.type.title === 'radio'"
              :qestion="qestion"
              :index="index"
            />
            <TestQuestionCheckbox
              v-else-if="qestion.type.title === 'checkbox'"
              :qestion="qestion"
              :index="index"
            />
            <TestQuestionOrdered
              v-else-if="qestion.type.title === 'order'"
              :qestion="qestion"
              :index="index"
            />
            <TestQuestionInputOne
              v-else-if="qestion.type.title === 'input_one'"
              :qestion="qestion"
              :index="index"
            />
            <TestQuestionRadio
              v-else
              :qestion="qestion"
              :index="index"
            />
          </div>
          <button type="submit" class="button">Проверить</button>
        </form>
      </div>
  </div>
</div>
</template>

<script>
import TestQuestionRadio from '../components/TestQuestionRadio.vue'
import TestQuestionCheckbox from '../components/TestQuestionCheckbox.vue'
import TestQuestionOrdered from '../components/TestQuestionOrdered.vue'
import TestQuestionInputOne from '../components/TestQuestionInputOne.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  components: {
    TestQuestionRadio,
    TestQuestionCheckbox,
    TestQuestionOrdered,
    TestQuestionInputOne
  },
  data() {
    return {
      count: 0,
        //  testName:''
        //  testNumm: $route.params.id
    }
  },
  computed:{
    testName () {
      return this.$store.getters.getTestTitleActive
    },
    qestions () {
      return this.$store.getters.getQuestions
    },
  },
  methods: {
    ...mapActions(["getQuestionsDb"]),
    onSubmit(e){
      const r = Array.from(e.target).filter(inp => inp.name.slice(0, 1) === "a")
        .map(inp => { return {[inp.name]: inp.value }})
       
        // router.push({ name: 'user', params: { username: 'erina' } })
      console.log(JSON.stringify(r))
      // this.getQuestionsDb(r)
    },
  },
  mounted(){
    this.getQuestionsDb(this.$route.params.id)
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
  .button{
    border: 1px solid rgb(171 171 171);
    padding: 5px 10px;
    border-radius: 5px;
    &:hover{
      background-color: rgb(225 225 221);
    }
  }
@media (min-width: 1024px) {
 
}
</style>
