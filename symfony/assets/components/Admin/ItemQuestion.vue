<template>
  <div
    class=" flex-shrink-1 shadow flex-row"
  >
    <div class="cont-question">
     
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
        v-else-if="questionType(question.type) === 'conformity'"
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
          :class="{published: !question.publishedAt}"
          title="Утвердить"
          @click.stop="approveQuestion"
          v-if="!question.publishedAt"
        >
          <i class="bi bi-file-check"></i>
        </div>
      </div>
      <div class="info"
        v-if="!question.publishedAt"
      >
        корректность вопроса необходимо подтвердить

      </div>
    </div>
  </div>
</template>
<!--  published-->
<script>
import TestQuestionRadio from './AdminTestQuestion/TestQuestionRadio.vue'
import TestQuestionCheckbox from './AdminTestQuestion/TestQuestionCheckbox.vue'
import TestQuestionOrdered from './AdminTestQuestion/TestQuestionOrdered.vue'
import TestQuestionInputOne from './AdminTestQuestion/TestQuestionInputOne.vue'
import TestQuestionConformity from './AdminTestQuestion/TestQuestionConformity.vue'

import { mapGetters, mapActions, mapMutations} from "vuex"

export default {
  props:['question', 'index'],
  components: {
    TestQuestionRadio,
    TestQuestionCheckbox,
    TestQuestionOrdered,
    TestQuestionInputOne,
    TestQuestionConformity,
   
  },
  data() {
    return {
     
    }
  },
  computed:{
    ...mapGetters([
      "getTest",
      "getActivePage",
      "getGonfimAction",
      "getGonfimMessage"
    ]),
  },
  methods: {
    ...mapActions([
      "deleteQuestionDb", 
      "setQuestion",
      "setConfirmMessage",
      "approveQuestionDb",
    ]),
    questionType(question){
      return question.title ? question.title : question
    },
    deleteVisibleConfirm(){
      this.setConfirmMessage("Вы, действительно хотите удалить вопрос?")
      let timerId = setInterval(() => {
        if (this.getGonfimAction) {
          clearInterval(timerId)
          if (this.getGonfimAction === "yes" ){this.deleteQuestin()}
        }
      }, 200);
      
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
        name: 'adminQuestionsCreate', 
        params: {
          testId: this.getTest.id,
          questionId: this.question.id,
          operation: 'edit'
        }
      })
    },
    async approveQuestion(){
      await this.approveQuestionDb({questionSend: [this.question.id]})
      this.question.publishedAt = true
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
  .published{
    background-color: rgb(167, 72, 69);
    &:hover{
      background-color: rgb(168, 108, 106);
    }
  }
  .info{
    display: inline-flex;
    background-color: rgb(167, 72, 69);
    margin-left: 20px;
    color: aliceblue;
    padding: 0 5px;
  }
@media (min-width: 1024px) {
 
}
</style>
