<template>
  <div
    class=" flex-shrink-1 shadow flex-row"
  >
    <div class="cont-question">
      <MyConfirm
        :message="confirmMessage"
        @yesConfirm="confirmYes"
        @noConfirm="confirmVisible = false"
        v-if="confirmVisible"
      />
      <TestQuestionRadio
        v-if="questionType(question.type) === 'radio'"
        :question="question"
        :index="index"
      />
      <TestQuestionCheckbox
        v-else-if="questionType(question.type) === 'checkbox'"
        :question="question"
        :index="index"
      />
      <TestQuestionInputOne
        v-else-if="questionType(question.type) === 'input_one'"
        :question="question"
        :index="index"
      />
      <TestQuestionOrdered
        v-else-if="questionType(question.type) === 'order'"
        :question="question"
        :index="index"
      />
      <TestQuestionConformity
        v-else-if="questionType(question.type) === 'conformi'"
        :question="question"
        :index="index"
      />
    </div>
    <div class="block-button">
      <div class="btn-group">
        <div class="btn btn-outline-primary btn-center"
          title="Редактировать"
          @click.stop="editQuestion"
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
  </div>
</template>
<!-- conformity -->
<script>
import TestQuestionRadio from './AdminTestQuestion/TestQuestionRadio.vue'
import TestQuestionCheckbox from './AdminTestQuestion/TestQuestionCheckbox.vue'
import TestQuestionOrdered from './AdminTestQuestion/TestQuestionOrdered.vue'
import TestQuestionInputOne from './AdminTestQuestion/TestQuestionInputOne.vue'
import TestQuestionConformity from './AdminTestQuestion/TestQuestionConformity.vue'
import MyConfirm from '../ui/MyConfirm.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"

export default {
  props:['question', 'index'],
  components: {
    TestQuestionRadio,
    TestQuestionCheckbox,
    TestQuestionOrdered,
    TestQuestionInputOne,
    TestQuestionConformity,
    MyConfirm
  },
  data() {
    return {
      confirmMessage: '',
      confirmVisible: false,
      confirmYes: null
    }
  },
  computed:{
    ...mapGetters([
      "getTest",
      "getActivePage"
    ]),
  },
  methods: {
    ...mapActions([
      "deleteQuestionDb", "setQuestion"
    ]),
    questionType(question){
      return question.title ? question.title : question
    },
    deleteVisibleConfirm(){
      this.confirmMessage = "Вы, действительно хотите удалить вопрос?"
      this.confirmVisible = true
      this.confirmYes = this.deleteQuestin
    },
    async deleteQuestin(){
      console.log('Удаляю вопрос № - ', this.question)
      console.log('роут  - ', this.$route)
      await this.deleteQuestionDb({
        id: this.question.id, 
        testId: this.getTest.id, 
        page: this.getActivePage,
      })
      this.confirmVisible = false
      this.confirmYes = null
    },
    editQuestion(){
      console.log('Редактировать № - ', this.question)
      console.log('тест № - ', this.getTest)
      this.setQuestion(this.question)
      this.$router.push({
        name: 'adminsQuestionsCreate', 
        params: {
          testId: this.getTest.id,
          questionId: this.question.id,
          operation: 'edit'
        }
      })
    }
  },
  async created(){
   
  },
} 

</script>
<style lang="scss" scoped>
  .block{
    background-color: rgb(207 207 199);
    padding: 10px ;
  }
  .block-button{
    padding: 0 10px 10px 10px;
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
  .cont-question{
    position: relative;
  }
@media (min-width: 1024px) {
 
}
</style>
