<template>
  <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <h2> {{ testName }}</h2>
      <div class="test">
        <p> {{ ticketTitle }}</p>  
      </div>
    </div>
    
    <div class="container">
      <div class="row">
        
          <div v-for="(qestion, index ) in qestions" 
            :key="qestion.id"
            class=" flex-shrink-1 shadow flex-row"
          >
           
            <TestQuestionRadio
              v-if="questionType(qestion.type) === 'radio'"
              :qestion="qestion"
              :index="index"
            />
            <TestQuestionCheckbox
              v-else-if="questionType(qestion.type) === 'checkbox'"
              :qestion="qestion"
              :index="index"
            />
            <TestQuestionInputOne
              v-else-if="questionType(qestion.type) === 'input_one'"
              :qestion="qestion"
              :index="index"
            />
            <TestQuestionOrdered
              v-else-if="questionType(qestion.type) === 'order'"
              :qestion="qestion"
              :index="index"
            />
            <TestQuestionConformity
              v-else-if="questionType(qestion.type) === 'conformity'"
              :qestion="qestion"
              :index="index"
            />
            <div class="btn-group" >
              <div class="btn btn-outline-primary btn-center"
                title="Редактировать"
                @click.stop="editTest"
              >
                <i class="bi bi-pencil"></i>
              </div>
              <div class="btn btn-outline-primary btn-center" 
              title="Удалить"
                @click.stop="deleteVisibleConfirm"
              >
                <i class="bi bi-trash3"></i>
              </div>
              <div class="btn btn-outline-primary btn-center"
                title="Утвердить"
                @click.stop="addQuestion"
              >
                <i class="bi bi-file-check"></i>
              </div>
            </div>
          </div>
          <Pagination
            type="getQuestionsTestIdDb"
          />
       
      </div>
  </div>
</div>
</template>

<script>
import TestQuestionRadio from '../../components/TestQuestion/TestQuestionRadio.vue'
import TestQuestionCheckbox from '../../components/TestQuestion/TestQuestionCheckbox.vue'
import TestQuestionOrdered from '../../components/TestQuestion/TestQuestionOrdered.vue'
import TestQuestionInputOne from '../../components/TestQuestion/TestQuestionInputOne.vue'
import TestQuestionConformity from '../../components/TestQuestion/TestQuestionConformity.vue'
import Pagination from "../../components/Pagination.vue"
import Loader from '../../components/ui/Loader.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  components: {
    TestQuestionRadio,
    TestQuestionCheckbox,
    TestQuestionOrdered,
    TestQuestionInputOne,
    TestQuestionConformity,
    Loader,
    Pagination
  },
  data() {
    return {
      isLoader: true,
      timeTicket: false,
      timeEnd: false,
      ticketId: this.$route.params.id,
      ticketTitle:"",
     
    }
  },
  computed:{
    ...mapGetters(["getSlug", "getRandomTicket", "getSelectTicket"]),
    testName () {
      return this.$store.getters.getTestTitleActive
    },
    qestions () {
      return this.$store.getters.getQuestions
    },
    
  },
   methods: {
    ...mapActions(["getQuestionsTestIdDb", ]),
    onSubmit(e){
      const ticket = Array.from(e.target).filter(inp => inp.id.slice(0, 1) === "a")
        .map(inp => { return {id:inp.name, answer: inp.value.split(',')}})
      
      this.saveResultTicketUser(ticket)
      this.$router.push({ path:'/result'})
    },
    timerEnd(){ //написать действия при окончании времени таймера
      this.timeEnd = true
    },
    questionType(question){
      return question.title ? question.title:question
    }
  },
  async created(){
    await this.getQuestionsTestIdDb({id: this.ticketId})
    this.isLoader = false
  },
 
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
  .btn-center{
    display: flex;
    align-items: center;
  }  
@media (min-width: 1024px) {
 
}
</style>
