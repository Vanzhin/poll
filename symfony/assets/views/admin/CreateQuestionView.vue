<template>
  <div class="block">
    <div class="title">
      <h2 
        v-if="getTest"
      >Тест: {{ getTest.title }}</h2>
      <h4
        v-if="operation === 'create'"
      >Создайте вопрос</h4>
      <h4
        v-if="operation === 'edit'"
      >Отредактируйте вопрос</h4>
      <div class="test">
        <div class="dropdown">
          <button class="dropdown-toggle" 
            type="button" id="dropdownMenuButton1" 
            data-bs-toggle="dropdown" aria-expanded="false"
          >
           Выберите тип вопроса
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li
              v-for="typeQuestion in typeQuestions"
            >
              <p class="dropdown-item" 
                @click="selectTypeQuestion=typeQuestion"
              >
                {{ typeQuestion.title }}
              </p>
            </li>
          </ul>
        </div> 
      </div>
    </div>
   <div class="container">
      <div class="row">
        Тип вопроса - {{ selectTypeQuestion.title }}
      </div>
    </div>
  </div>

  <div class="container"
   v-if="selectTypeQuestion !==''"
  >
    <div class="row">
      <form @submit.prevent="onSubmit">
        <input type="hidden" 
          name="question[test]" 
          :value="testId"
        >
        <input type="hidden" 
          name="question[type]" 
          :value="selectTypeQuestion.type"
        >
          <CreateQuestionRadio
            v-if="selectTypeQuestion.type === 'radio'"
          />
           <CreateQuestionCheckbox
            v-else-if="selectTypeQuestion.type === 'checkbox'"
          />
          <CreateQuestionInputOne
            v-else-if="selectTypeQuestion.type === 'input_one'"
          />
          <CreateQuestionOrdered
            v-else-if="selectTypeQuestion.type === 'order'"
          />
          <CreateQuestionConformity
            v-else-if="selectTypeQuestion.type === 'conformity'"
          />
          <br> 
        <button type="submit" class="button">Сохранить</button>
      </form>
    </div>
  </div>
</template>
 
<script>
  import MessageView from "../../components/ui/MessageView.vue"
  import CreateQuestionConformity from '../../components/createQuestion/CreateQuestionConformity.vue'
  import CreateQuestionInputOne from '../../components/createQuestion/CreateQuestionInputOne.vue'
  import CreateQuestionCheckbox from '../../components/createQuestion/CreateQuestionCheckbox.vue'
  import CreateQuestionRadio from '../../components/createQuestion/CreateQuestionRadio.vue'
  import CreateQuestionOrdered from '../../components/createQuestion/CreateQuestionOrdered.vue'
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      CreateQuestionCheckbox,
      CreateQuestionRadio,
      CreateQuestionInputOne,
      CreateQuestionOrdered,
      CreateQuestionConformity,
      MessageView
    },
    data() {
      return {
        count: 0,
        typeQuestions:[
          {id: 1, 
            type: "radio",
            title: "Один из многих",
          },
          {id: 2, 
            type: "checkbox",
            title: "Многие из многих",
          },
          {id: 3, 
            type: "input_one",
            title: "Поле ввода",
          },
          {id: 4, 
            type: "order",
            title: "Упорядочение",
          },
          {id: 5, 
            type: "conformity",
            title: "Соответсвие",
          },
        ],
        selectTypeQuestion: '',
        message: null,
        testId: this.$route.params.testId ? this.$route.params.testId: null,
        questionId: this.$route.params.questionId ? this.$route.params.questionId: null,
        operation: this.$route.params.operation
      }
    },
    computed:{ 
      ...mapGetters(["getAutchUserToken", "getMessage", "getTest", "getQuestion"]),
    },
   
    methods: { 
      ...mapActions(["saveQuestionDb", "setMessage"]),
      ...mapMutations([]),
      setSelectTypeQuestion(){},
      async onSubmit(e){
        const trueAnswer = Array.from(e.target).filter(inp => inp.id === "answer_true")
        if (!trueAnswer[0].value) {
          const message = {
            err: true, 
            mes: 'Укажите правильный ответ!'
          }
          this.setMessage(message)
          return
        }
        const questionSend = e.target
        
        if ( this.operation === 'edit'){
          await this.editQuestionDb({questionSend, token: this.getAutchUserToken, id:+this.questionId})
        } else if ( this.operation === 'create'){
          await this.saveQuestionDb({questionSend, token: this.getAutchUserToken})
        }

        this.message = !this.getMessage.err
        let timerId = setInterval(() => {
          if ( !this.getMessage) {
            clearInterval(timerId)
            if (this.message ){this.selectTypeQuestion = ""}
          }
        }, 200);



        // this.$router.push({ path:'/result'})
      },
    },
    async mounted(){
      
    },
    created(){
      console.log(this.typeQuestions.find(item => item.type = this.getQuestion.type))
      if ( this.operation === 'edit'){
        const item = this.typeQuestions.find(item => item.type = this.getQuestion.type)
        this.selectTypeQuestion = {
          id: item.id, 
          type: item.type.title ? item.type.title : item.type,
          title: item.title,
        }
      }
    }
   
 } 
 
</script>
<style lang="scss" scoped>
  .button{
    padding: 5px 10px;
    transition: all 0.1s ease-out;
    &:hover{
      background-color: rgb(156, 156, 154);
    }
  }
 @media (min-width: 1024px) {
  
 }
</style>