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
    </div>
    <div class="btn-group" >
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
</template>

<script>
import TestQuestionRadio from './AdminTestQuestion/TestQuestionRadio.vue'
import TestQuestionCheckbox from './AdminTestQuestion/TestQuestionCheckbox.vue'
import TestQuestionOrdered from './AdminTestQuestion/TestQuestionOrdered.vue'
import TestQuestionInputOne from './AdminTestQuestion/TestQuestionInputOne.vue'
import TestQuestionConformity from './AdminTestQuestion/TestQuestionConformity.vue'
import Pagination from "../Pagination.vue"
import MyConfirm from '../ui/MyConfirm.vue'
import Loader from '../ui/Loader.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"

export default {
  props:['qestion', 'index'],
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
      "deleteQuestionDb", 
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
      console.log('Удаляю вопрос № - ', this.qestion)
      console.log('роут  - ', this.$route)
      await this.deleteQuestionDb({
        id: this.qestion.id, 
        testId: this.getTest.id, 
        page: this.getActivePage,
      })
      this.confirmVisible = false
      this.confirmYes = null
    },
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
