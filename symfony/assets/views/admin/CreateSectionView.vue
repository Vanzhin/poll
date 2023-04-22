<template>
  <div class="container">
    <div class="row">
      <div class="block">
        <CloseVue
          :toLink="{name: 'adminTestSections', params: {id: $route.params.testId }  }"
        />
        <div class="title">
          <h2>Форма редактирования секций</h2>
        </div>
      </div>
    </div>
  </div>
  <Loader
    v-if="isLoader"
  />
  <div class="container"
    v-else
  >
    <div class="row">
      <form @submit.prevent="onSubmit">
        <input type="hidden" 
          name="category" 
          :value="testId"
        >
        <p class="label"><b>Секция: </b></p>
        <div class="custom-radio img_block">
          <input required
            name="title"
            v-model= "title"
            class="textarea_input" 
          /> 
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="title = ''"
            v-if="title !== ''"
          ></i>
        </div>
        <p class="label"><b>Укажите минимальное количество вопросов для прохождения секции: </b></p>
        <div class="custom-radio img_block">
          <input required
            name="questionCountToPass"
            v-model= "questionCountToPass"
            class="textarea_input"
            pattern="[0-9]{1,10}" 
          /> 
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="questionCountToPass = ''"
            v-if="questionCountToPass !== ''"
          ></i>
        </div>
        <div class="block_number">
          <label for="number" class="label"> Количество вопросов:</label>
          <label for="number" class="label"> {{ numberQuestions }}</label>
        </div>
        <div class="questions-block">  
          <div class="questions-item"
            
            v-for="(question, index ) in questions"
            :key="question.id"
            :title="question.title"
            @click="selectQuestion({question, index })"
            :class="{'questions-item-select': question.select}"
            >  
            {{ index + 1 }}
          </div>
        </div>
        <br> 
        {{ sectionQuestions }}  
        <br> 
        <button type="submit" class="button">Сохранить</button>
      </form>
    </div>
  </div>
</template>
 
<script>
  import Loader from '../../components/ui/LoaderView.vue'
  import CloseVue from '../../components/ui/Close.vue'
  import { RouterLink } from 'vue-router'
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      CloseVue
    },
    data() {
      return {
        testId: undefined,
        title: "",
        description: "",
        questionCountToPass: 1,
        message: null,
        isLoader: true,
        operation: this.$route.params.operation,
        numberQuestions: 0,
        questions:[],
        sectionQuestions: []
      }
    },
    computed:{ 
      ...mapGetters([
        "getMessage", 
        "getQuestions", 
        "getTest",
        "getTestId",
        "getQuestionsSection",
        "getSection"
        
      ]),
      getTest () {
        const test = this.$store.getters.getTest
        console.log(test)
        return test
      },
    },
   
    methods: { 
      ...mapActions([
        "setMessage", 
        "selectTestId", 
        "getQuestionsTestIdDb",
        "createSection",
        "getQuestionsSectionIdDb",
        
      ]),
      ...mapMutations([]),
      async onSubmit(e){
        const questionSend = e.target
        const section = {
          "title": this.title,
          "test": +this.testId, 
          "question": [...this.sectionQuestions],
          "questionCountToPass": +this.questionCountToPass
        }
        if ( this.operation === 'edit'){
          await this.createSection({
            section, 
            id: +this.$route.params.sectionId, 
            operation: 'edit'
          })
        } else if ( this.operation === 'create'){
          await this.createSection({section})
        }
        this.message = !this.getMessage.err
        let timerId = setInterval(() => {
          if ( !this.getMessage ) {
            clearInterval(timerId)
            if (this.message ){this.$router.go(-1)}
          }
        }, 200);
      },
      
      selectQuestion({question,index }){
        if (question.select) {
          question.select = false
          this.sectionQuestions=[...this.sectionQuestions.filter(
            item => item !== question.id
          )] 
        } else {
          question.select = true
          this.sectionQuestions.push(question.id)
        }
        this.numberQuestions = this.sectionQuestions.length
      }
    },
    async mounted(){
      
    },
    async created() {
      this.isLoader = true
      this.testId = this.$route.params.testId
      await this.getQuestionsTestIdDb({id: this.testId, limit: 1000})
      this.questions = [...this.getQuestions]
      this.testId = this.$route.params.testId
      if ( this.$route.params.operation === 'create'){
        
      } 
      if ( this.$route.params.operation === 'edit'){
        await this.getQuestionsSectionIdDb({id: +this.$route.params.sectionId})
        console.log('getQuestionsSection -', this.getQuestionsSection)
        this.title = this.getSection.title ?? ''
        this.questionCountToPass = this.getSection.questionCountToPass ?? ''
        this.sectionQuestions =this.getQuestionsSection? this.getQuestionsSection.map((question)=>{
            const num = this.questions.findIndex((item)=>item.id === question.id)
            console.log(num)
            console.log(this.questions[num])
            if (num >= 0) {this.questions[num].select = true}
            return question.id
          }
        ) : []
        this.numberQuestions = this.sectionQuestions.length
      }
      this.isLoader = false
      
    }
   
 } 
 
</script>
<style lang="scss" scoped>
.block{
  margin-top: 10px;
}
.block_number{
    display: flex;
  }
.button{
    padding: 5px 10px;
    transition: all 0.1s ease-out;
    &:hover{
      background-color: rgb(156, 156, 154);
    }
  }
  .label{
    display:block;
    margin: 0 10px;
  }
  .textarea_input{
    width: 80%;
    padding: 0;
    padding-left: 10px;
    margin: 5px;
  }
  .questions{
    &-block{
      width: 100%;
      display: flex;
      flex-wrap: wrap;
    }
    &-item{
      width: 45px;
      height: 30px;
      border-radius: 6px;
      background-color: rgb(141 135 135);
      border: 2px solid rgb(141 135 135);
      margin: 3px;
      text-align: center;
      &:hover{
        border: 2px solid rgb(36, 34, 34);
        cursor: pointer;
      }
      &-select{
        background-color: rgb(139, 118, 72);
      }
    }
  }
 @media (min-width: 1024px) {
  
 }
</style>