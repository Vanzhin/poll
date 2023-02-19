<template>
  <div class="block">
    <div class="title">
      <h2>Создание вопроса</h2>
      <div class="test">
        <div class="dropdown">
          <button class="  dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
           Выберите тип вопроса
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            
            <li
              v-for="typeQuestion in typeQuestions"
            ><p class="dropdown-item" @click="selectTypeQuestion=typeQuestion.type">{{ typeQuestion.title }}</p></li>
           
          </ul>
        </div> 
      </div>
    </div>
   <div class="container">
      <div class="row">
        h1 --   {{ selectTypeQuestion }}
      </div>
    </div>
  </div>

  <div class="container"
   v-if="selectTypeQuestion !==''"
  >
    <div class="row">
      <form @submit.prevent="onSubmit">
        <input type="hidden" 
          name="question_type" 
          :value="selectTypeQuestion"
        >
          <CreateQuestionRadio
            v-if="selectTypeQuestion === 'radio'"
          />
          <!-- <CreateQuestionCheckbox
            v-else-if="selectTypeQuestion === 'checkbox'"
          />
          <CreateQuestionInputOne
            v-else-if="selectTypeQuestion === 'input_one'"
          />
          <CreateQuestionOrdered
            v-else-if="selectTypeQuestion === 'order'"
          />
          <CreateQuestionConformity
            v-else-if="selectTypeQuestion === 'conformity'"
          /> -->
          <br> 
          <div style="width: 100%;">
            <MessageView
              v-if="message"
              :message="message"
            />
          </div>
        <button type="submit" class="button">Сохранить</button>
      </form>
    </div>
  </div>
</template>
 
<script>
  import MessageView from "../components/ui/MessageView.vue"
  import CreateQuestionRadio from '../components/createQuestion/CreateQuestionRadio.vue'
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      CreateQuestionRadio,
      MessageView
    },
    data() {
      return {
        count: 0,
          //  testName:''
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
        message: null
      }
    },
    computed:{ 
      ...mapGetters(["getAutchUserToken"]),
    
    },
   
    methods: { 
      ...mapActions(["saveQuestionDb"]),
      ...mapMutations([]),
      setSelectTypeQuestion(){},
      onSubmit(e){
        console.dir(e.target)
        const trueAnswer = Array.from(e.target).filter(inp => inp.name === "answer_true")
        console.dir(trueAnswer[0].value)
        if (!trueAnswer[0].value) {
          this.message = {
            mes: 'Укажите правильный ответ!'
          }
          setTimeout(()=>this.message=null, 5000)
          return
        }
        const questionSend = Array.from(e.target).filter(inp => inp.name !== "")
      // const ticket = Array.from(e.target).filter(inp => inp.id.slice(0, 1) === "a")
        // .map(inp => { return {id:inp.name, answer: inp.value.split(',')}})
      
       this.saveQuestionDb({questionSend, token:this.getAutchUserToken})
      // this.$router.push({ path:'/result'})
    },
    },
      async mounted(){
      
     }
   
 } 
 
</script>
<style lang="scss" scoped>
 @media (min-width: 1024px) {
  
 }
</style>