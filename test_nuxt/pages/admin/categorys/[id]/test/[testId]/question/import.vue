<template>
  <div class="block">
    <div class="title">
      Импортировать тест из файла
    </div>
    <hr/>
   <div class="container">
      <div class="row">
        <form @submit.prevent="onSubmit" class="form-import">
          <div
            v-if="!this.$route.params.id"
          >
            <label class="label">Укажите тест для импорта</label>
            <select  name="hero" v-model="selectedId" class="select">
              <option disabled>Выберите тест</option>
              <option 
                v-for="(item) in getTests"
                :key="item.id"
                :value="item.id" 
              >{{item.title}}</option>
            </select>

            {{ selectedId }}
          </div>
          <label class="label ">Выберите файл для импорта   <i class="bi bi-question-square" 
            title="Для загрузки возможен файл .txt или архив .zip(до 25mB) с файлом .txt и 
            картинками для вопросов зазмером до 512kB."
            ></i></label>
          <br>
          <div class="d-flex align-items-center">
            <input  class="input_file" type="file"  
              @change="changeFileValue" 
              name="file"
              :value="testValue"
            >
            <div class="custom-block">
              <i class="bi bi-x-lg custom-close" title="Отменить выбор"
                @click="testFileDelete()"
                v-if="typeof testFile === 'object'"
              ></i>
            </div>
          </div>
          <br> 
          <button type="submit" class="button">Загрузить</button>
        </form>
      </div>
      
      <div class="row"
        v-if="(getQuestionsImportError && (Array.isArray(getQuestionsImportError) || 
        typeof getQuestionsImportError === 'object'
        ))"
      >
      
        <br/>
        <hr><br/>
        <h5>При импорте обнаруженны ошибки в следующих вопросах:</h5>
        <ol>
          <li 
            v-for="(item) in getQuestionsImportError"
            :key="item.id"
            class="question-item"
          >
              <div
              v-if="typeof item.type === 'object'"
              >
                <h6 
                  v-for="(type) in item.type"
                  :key="type"
                  >{{type}}!
                </h6>
              </div>
              <h6 v-else>{{item.type}}!</h6>
              <p>Вопрос: {{ item.question.title || ""}}</p>
              <div
                v-if="item.question.variant"
                class="question-item-variant"
              >
                Варинты ответов:
                <div class="variants "
                  v-for="(variant) in item.question.variant"
                  :key="variant.title"
                >
                <i class="bi bi-check-square "
                  v-if="variant.correct"
                ></i>
                {{ variant.title || '' }}
                </div>
             
            </div>
          </li>
        </ol>
      </div>
    </div>
  </div>
</template>
<script setup>
  definePageMeta({
    layout: "admin",
    middleware: 'authadmin'
  })
  import { useModalStore } from '@/stores/ModalStore'
  import { useQuestionsStore } from '@/stores/QuestionStore'
  
  const modal = useModalStore()
  const questions = useQuestionsStore()
  const route = useRoute()

  const testFile = ref('')
  const testValue = ref(null)
  const selectedId = ref(null) 
  const message = ref(null) 
  const questionsImgs = ref([])
  const questionsImagesValue = ref('') 
  const imagesMaxSize = ref([])
  const maxSizeImg = ref(500)
  const inputImage = ref([])
  
 
  async function onSubmit(e){
        
        if (!testValue.value) {
          const message = {
            error: true, 
            message: 'Выберите файл'
          }
          modal.setMessage(message)
          return
        }
        const testFile = e.target
        let testId = ''
        if (route.params.testId) {
          testId = route.params.testId
        } else if (!selectedId.value) {
          const message = {
            error: true, 
            message: 'Укажите тест для импорта'
          }
            modal.setMessage(message)
          return
        } else {
          testId = selectedId.value
        }
        
        await questions.importQuestionsFileDb({id: testId, testFile})
       
        message.value = !modal.getMessage.err
        // this.testFileDelete()
        let timerId = setInterval(() => {
          if ( !modal.getMessage ) {
            clearInterval(timerId)
            
            if (message.value) {
              navigateTo(`/admin/categorys/${route.params.id}/test/${testId}/questions`)
              
            }
          }
        }, 200);
      }
      function changeFileValue(e){
        if (typeof e.target.files[0] === 'object'){
          
          testFile.value = e.target.files[0]
          testValue.value = e.target.value
          modal.setMessage(null)
          const regexp = new RegExp("zip", 'i');
          const regexp1 = new RegExp("txt", 'i');
          if (!(regexp.test(e.target.files[0].name) || regexp1.test(e.target.files[0].name))) {
            const message = {
              error: true, 
              message: 'Для импорта необходимо выбрать текстовый файл или архив zip с текстовым файлом и картинками.'
            }
            modal.setMessage(message)
            testFileDelete()
          }
        }
      }
      function testFileDelete(){
        testFile.value = ''
        testValue.value = null
        modal.setMessage(null)
      }
      function changeQuestionsImgs(e){
        if (typeof e.target.files[0] === 'object'){
          inputImage.value = e.target
          const  files = [...e.target.files]
          let arrayImg = []
          for(let i = 0; i < files.length; i++){
            let item = files[i]
            arrayImg.push({
              url: URL.createObjectURL(item),
              name: item.name,
              size: (item.size / 1024).toFixed(0)
            })
            if ( (item.size / 1024).toFixed(0) > maxSizeImg.value){
              imagesMaxSize.value.push(item.name)
            }
          }
          questionsImgs.value = arrayImg
        }
      }
      function questionImgDelete(img, index){
        imagesMaxSize.value = imagesMaxSize.value.filter(item => item != img.name)
        questionsImgs.value = questionsImgs.value.filter(item => item.name !== img.name)

        let dt = new DataTransfer()
        for(let i=0; i <= inputimg.value.files.length-1; i++){
          if(i !== index) {dt.items.add(inputimg.files[i])}
        }
        inputimg.files = dt.files
      }
</script>
<style lang="scss" scoped>
.variants{
  position: relative;
  & i {
    position: absolute;
    left: -20px;
    top: 4px;
  }
}
  .form-import{
    margin-bottom: 15px;
    font-family: 'Lato';
    font-style: normal;
  }
  .question-item{
    margin-top: 10px;
    margin-left: 15px;
    h6{
      color: rgb(223, 62, 62);
    }
    &-variant{
      padding-left: 30px;
    }
  }
  .input_file{
    margin-top: 20px;
    margin-bottom: 20px;
    
  }
  .button{
    width: 148px;
    height: 37px;
    background: #269EB7;
    border: 1px solid #269EB7;
    border-radius: 6px;
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    
    transition: all 0.1s ease-out;
    color: #FFFFFF;
    &:hover{
      background-color: #FFFFFF;
      color:#269EB7;
    }
  }
  .custom{
    &-block{
      width: 26px;
      height: 26px;
      display: flex;
      align-items: center;
      justify-content: center;
    };
    &-close{
      cursor: pointer;
      margin: 10px;
      transform: scale(1);
      transition: all 0.5s ease-out;
      &:hover{
        color: rgb(185, 48, 14);
        transform: scale(1.25);
      }
    }
  }
  .img_conteiner{
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 5px;
    padding: 5px;
    overflow: auto;
    max-height: 57vh;
    background-color: rgb(228 240 251);
  }
  .img_block{
    display: flex;
    align-items: flex-start;
    margin: 3px;
    
    & p {
      margin:0 ;
    }
    & i {
      margin: 10px;
      transition: all 0.5s ease-out;
      &:hover{
        color: rgb(185, 48, 14);
        transform: scale(1.25);
      }
    }
  }
  .img_item{
    outline: 2px rgb(228 240 251);
    &-error{
      outline: 2px solid rgb(233, 50, 18);
      background-color: rgba(245, 30, 14, 0.336);
    }
  }
  .select{
    width: 100%;
  }
 @media (min-width: 1024px) {
  
 }
</style>