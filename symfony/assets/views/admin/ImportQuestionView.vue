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
            <select  name="hero" v-model="selectedId">
              <option disabled>Выберите тест</option>
              <option 
                v-for="(item, index) in getTests"
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
          <!-- <div class="mb-3 w-100">
            <div class="img_conteiner"
              v-if="questionsImgs.length > 0"
            >
              <div class="img_block"
                v-for="(img, index) in questionsImgs"
              >
                <div class="img_item"
                  :class="{'img_item-error': img.size > maxSizeImg }"
                >
                  <img :src="img.url" width="100" />
                  <p>{{ img.name }}</p>
                  <p>{{ img.size }}Kb</p>
                </div> 
                <i class="bi bi-x-lg custom-close" title="Удалить изображениe"
                  @click="questionImgDelete(img, index)"
                ></i>
              </div>
            </div>
            <label class="label"
              v-if="questionsImgs.length === 0"
            >Прикрепить файлы с изображениями</label>
            <label class="label"
              v-else
            >Изменить изображения</label>
            
            <input  class="" type="file" accept="image/*" multiple="true" id="inputimg"
              @change="changeQuestionsImgs" 
              :v-model="questionsImagesValue"
              name = 'image[]'
            > <br>
            <label class="label"
              v-if="imagesMaxSize.length > 0"
            >Имеются файлы размером больше 500Кб - {{ imagesMaxSize.length  }}шт.</label>
          </div> -->
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
            v-for="(item, index) in getQuestionsImportError"
            class="question-item"
          >
           
              <h6 
                v-if="typeof item.type === 'object'"
                v-for="(type) in item.type"
                >{{type}}!</h6>
              <h6 v-else>{{item.type}}!</h6>
              <p>Вопрос: {{ item.question.title || ""}}</p>
              <div
                v-if="item.question.variant"
                class="question-item-variant"
              >
                Варинты ответов:
                <div class="variants "
                  v-for="(variant, index) in item.question.variant"
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
 
<script>
  
  
  import { mapGetters, mapActions, mapMutations} from "vuex"
  import { onUnmounted } from "vue"
  export default {
    components: {
     
     
    },
    data() {
      return {
        testFile: '',
        testValue: null,
        selectedId: null,
        message: null,
        questionsImgs: [],
        questionsImagesValue: '',
        imagesMaxSize:[],
        maxSizeImg: 500,
        inputImage:[],

        
      }
    },
    computed:{ 
      ...mapGetters([
        "getMessage",
        "getTests",
        "getQuestionsImportError"
      ]),
      imagesMaxSizeArr(){
        return this.imagesMaxSize.length
      },
      
    },
   
    methods: { 
      ...mapActions([
        "importQuestionsFileDb", 
        "setMessage", 
        "getTestsDB", 
        "setQuestionsImportError",
        "setIsLoaderStatus"
      ]),
      ...mapMutations([]),
      async onSubmit(e){
        
        if (!this.testValue) {
          const message = {
            error: true, 
            message: 'Выберите файл'
          }
          this.setMessage(message)
          return
        }
        const testFile = e.target
        let testId = ''
        if (this.$route.params.id) {
          testId = this.$route.params.id
        } else if (!this.selectedId) {
          const message = {
            error: true, 
            message: 'Укажите тест для импорта'
          }
            this.setMessage(message)
          return
        } else {
          testId = this.selectedId
        }
        
        await this.importQuestionsFileDb({id: testId, testFile})
       
        this.message = !this.getMessage.err
        // this.testFileDelete()
        let timerId = setInterval(() => {
          if ( !this.getMessage ) {
            clearInterval(timerId)
            // if (this.message){this.$router.go(-1)} adminTest
            if (this.message)
            {
              this.$router.push({
                name: 'adminTestQuestions', 
                params: {id: testId} 
              })
            }
          }
        }, 200);
      },
      changeFileValue(e){
        if (typeof e.target.files[0] === 'object'){
          
          this.testFile = e.target.files[0]
          this.testValue = e.target.value
          this.setQuestionsImportError(null)
          const regexp = new RegExp("zip", 'i');
          const regexp1 = new RegExp("txt", 'i');
          if (!(regexp.test(e.target.files[0].name) || regexp1.test(e.target.files[0].name))) {
            const message = {
              error: true, 
              message: 'Для импорта необходимо выбрать текстовый файл или архив zip с текстовым файлом и картинками.'
            }
            this.setMessage(message)
            this.testFileDelete()
          }
        }
      },
      testFileDelete(){
        this.testFile = ''
        this.testValue = null
        this.setQuestionsImportError(null)
      },
      changeQuestionsImgs(e){
        if (typeof e.target.files[0] === 'object'){
          this.inputImage = e.target
          const  files = [...e.target.files]
          let arrayImg = []
          for(let i = 0; i < files.length; i++){
            let item = files[i]
            arrayImg.push({
              url: URL.createObjectURL(item),
              name: item.name,
              size: (item.size / 1024).toFixed(0)
            })
            if ( (item.size / 1024).toFixed(0) > this.maxSizeImg){
              this.imagesMaxSize.push(item.name)
            }
          }
          this.questionsImgs = arrayImg
        }
      },
      questionImgDelete(img, index){
        this.imagesMaxSize = this.imagesMaxSize.filter(item => item != img.name)
        this.questionsImgs = this.questionsImgs.filter(item => item.name !== img.name)

        let dt = new DataTransfer()
        for(let i=0; i<=inputimg.files.length-1; i++){
          if(i !== index) {dt.items.add(inputimg.files[i])}
        }
        inputimg.files = dt.files
      }
    },
    async mounted(){},
    async created(){
      
      // if (!(this.getMessage.length > 0)){
        await this.getTestsDB({limitMax:true}) 
      // }       
      
    },
    unmounted(){
      this.setQuestionsImportError(null)
    }
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
 @media (min-width: 1024px) {
  
 }
</style>