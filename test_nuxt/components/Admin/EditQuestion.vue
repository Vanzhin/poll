<template>
  <div class="question-form">
    <div class="block">
      <div class="title">
        <h2 
          v-if="tests.getTest"
        >Тест: {{ tests.getTest.title }}</h2>
        <h4
          v-if="operation === 'create'"
        >Создайте вопрос</h4>
        <h4
          v-if="operation === 'edit'"
        >Отредактируйте вопрос</h4>
        <div class="test"
          v-if="operation === 'create'"
        >
          <div class="dropdown">
            <UiDropDown
              buttonTitle = "Выберите тип вопроса"
              :listItem = "typeQuestions"
              dropPozition = "bottom"
              @item-menu-click = "(item) => selectTypeQuestion=item"
            />
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
        <form @submit.prevent="onSubmit" >
          <input type="hidden" 
            name="question[test]" 
            :value="testId"
          >
          <input type="hidden" 
            name="question[type]" 
            :value="selectTypeQuestion.type"
          >
            <AdminCreateQuestionRadio
              v-if="selectTypeQuestion.type === 'radio'"
            />
            <AdminCreateQuestionCheckbox
              v-else-if="selectTypeQuestion.type === 'checkbox'"
            />
            <AdminCreateQuestionInputOne
              v-else-if="selectTypeQuestion.type === 'input_one'"
            />
            <AdminCreateQuestionOrdered
              v-else-if="selectTypeQuestion.type === 'order'"
            />
            <AdminCreateQuestionConformity
              v-else-if="selectTypeQuestion.type === 'conformity'"
            />
            <br> 
          <button type="submit" class="button">Сохранить</button>
        </form>
      </div>
    </div>
  </div>
</template>
<script setup>

import { useModalStore } from '@/stores/ModalStore'
import { useQuestionsStore } from '@/stores/QuestionStore'
import { useTestsStore  } from '@/stores/TestsStore'
const modal = useModalStore()
const questions = useQuestionsStore()
const tests = useTestsStore()
const route = useRoute()
const router = useRouter();
const toggle = ref({toggle: false, click: 0})
const count = ref( 0)
const typeQuestions = [
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
]
const selectTypeQuestion = ref('')
const message = ref(null)
const testId = route.params.testId ? route.params.testId: null
const questionId = route.params.questionId ? +route.params.questionId: null
const operation = route.params.operation

async function onSubmit(e){
  const trueAnswer = Array.from(e.target).filter(inp => inp.id === "answer_true")
  if (!trueAnswer[0].value) {
    const message = {
      error: true, 
      message: 'Укажите правильный ответ!'
    }
    modal.setMessage(message)
    return
  }
  const questionSend = new FormData(e.target);
  
  if ( route.params.operation === 'edit'){
    await questions.saveQuestionDb({questionSend, id: questionId})
  } else if ( route.params.operation === 'create'){
    await questions.saveQuestionDb({questionSend, })
  }

  message.value = !modal.getMessage.err
  let timerId = setInterval(() => {
    if ( !modal.getMessage) {
      clearInterval(timerId)
      if (message.value){
        selectTypeQuestion.value = ""
        if ( operation === 'edit'){
          router.go(-1)
        }
      }
    }
  }, 200);
  
}
onMounted(async () => {
  if ( operation === 'edit'){
      if (!tests.getTest) {
        await tests.getTestIdDb({id: this.testId})
      }
      if (!questions.getQuestion) {
        await questions.getQuestionIdDb ({id: questionId})
      }

      
      const typeQuestion = questions.getQuestion.type.title ? questions.getQuestion.type.title : questions.getQuestion.type
      
      const item = typeQuestions.find(item => item.type = questions.getQuestion.type)
      selectTypeQuestion.value = {
        id: item.id, 
        type: item.type.title ? item.type.title : item.type,
        title: item.title,
      }
      console.log(selectTypeQuestion.value)
    }
})         

</script>
<style lang="scss" scoped>
.question-form{
overflow-y: auto;
height: 80vh;
}
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