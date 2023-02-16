<template>
  <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    
    <div class="title">
      <Timer
      v-if="timeTicket"
      :time="0.1"
      @time-end="timerEnd"
    />
      {{ timeEnd }}
      <h2> {{ testName.title }}</h2>
      <div class="test">
        <p>Билет №: {{ $route.params.id }}</p>  
      </div>
    </div>
    
    <div class="container">
      <div class="row">
        <form @submit.prevent="onSubmit">
          <div v-for="(qestion, index ) in qestions" 
            :key="qestion.id"
          >
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
            <TestQuestionInputOne
              v-else-if="qestion.type.title === 'input_one'"
              :qestion="qestion"
              :index="index"
            />
            <TestQuestionOrdered
              v-else-if="qestion.type.title === 'order'"
              :qestion="qestion"
              :index="index"
            />
            <TestQuestionConformity
              v-else-if="qestion.type.title === 'conformity'"
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
import TestQuestionConformity from '../components/TestQuestionConformity.vue'
import Timer from '../components/ui/Timer.vue'
import Loader from '../components/ui/Loader.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  components: {
    TestQuestionRadio,
    TestQuestionCheckbox,
    TestQuestionOrdered,
    TestQuestionInputOne,
    TestQuestionConformity,
    Loader,
    Timer
  },
  data() {
    return {
      isLoader: true,
      timeTicket: false,
      timeEnd: false,
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
    ...mapActions(["getQuestionsDb", "setResultDb"]),
    async onSubmit(e){
      const r = Array.from(e.target).filter(inp => inp.id.slice(0, 1) === "a")
        .map(inp => { return {id:inp.name, answer: inp.value.split(',')}})
      const ticket = JSON.stringify(r)
      this.$router.push({ path:'/result'})
      console.log(ticket)
      await this.setResultDb(ticket)
    },
    timerEnd(){
      this.timeEnd = true
    }
  },
  async mounted(){
    await this.getQuestionsDb(this.$route.params.id)
    if (this.$route.params.id === "rnd20t" ) {this.timeTicket = true}
    this.isLoader = false
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
