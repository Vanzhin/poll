<template>
  <div>
    <div class="container">
    <div class="row">
      <div class="block">
        <UiClose
          :toLink="`/admin/categorys/${route.params.id}/test/${route.params.testId}/sections`"
        />
        <div class="title">
          <h2>Форма редактирования секций</h2>
        </div>
      </div>
    </div>
  </div>
  
  <div class="container">
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
          <label for="number" class="label"> Количество вопросов в секции:</label>
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
  </div>
</template>
<script setup>
  import { useTestsStore  } from '@/stores/TestsStore'
  import { useQuestionsStore } from '@/stores/QuestionStore'

  import { useSectionsStore  } from '../../stores/SectionsStore'


  import { useModalStore } from '@/stores/ModalStore'

  const tests = useTestsStore()
  const questionsSt = useQuestionsStore()
  const sections = useSectionsStore()
  const modal = useModalStore()

  const route = useRoute()
  const router = useRouter()

  const testId = ref(route.params.testId)
  const title = ref("")
  const questionCountToPass = ref(1)
  const message = ref( null)
  const operation = ref(route.params.operation)
  const numberQuestions = ref( 0)
  const questions = ref([] )
  const sectionQuestions= ref( [])

  async function onSubmit(e){
    
    const section = {
      "title": title.value,
      "test": +testId.value, 
      "question": [...sectionQuestions.value],
      "questionCountToPass": +questionCountToPass.value
    }
    if ( operation.value === 'edit'){
      await sections.createSectionDb({
        section, 
        id: +route.params.sectionId, 
        testId: testId.value,
        page: +route.params.page
      })
    } else if ( operation.value === 'create'){
      await sections.createSectionDb({
        section,
        testId: testId.value
      })
    }
    message.value = !modal.getMessage.err
    let timerId = setInterval(() => {
      if ( !modal.getMessage ) {
        clearInterval(timerId)
        if (message.value ){router.go(-1)}
      }
    }, 200);
  }  

  function selectQuestion({question }){
    if (question.select) {
      question.select = false
      sectionQuestions.value=[...sectionQuestions.value.filter(
        item => item !== question.id
      )] 
    } else {
      question.select = true
      sectionQuestions.value.push(question.id)
    }
    numberQuestions.value = sectionQuestions.value.length
  }

  onMounted(async() => {
      await questionsSt.getAdminQuestionsTestIdDb({id: testId.value, limit: 1000})
      questions.value = [...questionsSt.getQuestions]
      
      if ( route.params.operation === 'create'){
        
      } 
      if ( route.params.operation === 'edit'){
        await questionsSt.getQuestionsSectionIdDb({id: +route.params.sectionId})
        
        title.value = sections.getSection.title ?? ''
        questionCountToPass.value = sections.getSection.questionCountToPass ?? ''
        sectionQuestions.value = questionsSt.getQuestionsSection ? questionsSt.getQuestionsSection.map((question)=>{
            const num = questions.value.findIndex((item)=>item.id === question.id)
            
            if (num >= 0) {questions.value[num].select = true}
            return question.id
          }
        ) : []
        numberQuestions.value = sectionQuestions.value.length
      }
      
      
    })


</script>
<style lang="scss" scoped>

.img_block{
    display: flex;
    align-items: flex-start;
    & i {
      margin: 10px;
      transition: all 0.5s ease-out;
      &:hover{
        color: rgb(185, 48, 14);
        transform: scale(1.25);
        cursor: pointer;
      }
    }
  }

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