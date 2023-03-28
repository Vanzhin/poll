<template>
  
  <div class="block">
    <div class="title">
      <h2>Импортировать тест из файла</h2>
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
          <label class="label">Выберите файл для импорта</label>
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
        v-if="getQuestionsImportError"
      >
        <br/>
        <hr><br/>
        <h5>При импорте обнаруженны ошибки в следующих вопросах:</h5>
        <div 
          v-for="(item, index) in getQuestionsImportError"
          class="question-item"
        >
          <ul>
            <li>
              <div>

                <h6 
                  v-if="typeof item.type === 'object'"
                  v-for="(type) in item.type"

                >{{type}}!</h6>
                <h6 v-else>{{item.type}}!</h6>
                <p>Вопрос: {{ item.question.title}}</p>
                <div
                  v-if="item.question.variant"
                  class="question-item-variant"
                >
                  Варинты ответов:
                  <li
                    v-for="(variant, index) in item.question.variant"
                  >
                    {{ variant.title }}
                  </li>
                </div>
              </div>
            </li>
          </ul>
        </div>
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
        message: null
      }
    },
    computed:{ 
      ...mapGetters([
        "getAutchUserToken", 
        "getMessage",
        "getTests",
        "getQuestionsImportError"
      ]),
    },
   
    methods: { 
      ...mapActions([
        "importQuestionsFileDb", 
        "setMessage", 
        "getTestsDB", 
        "setQuestionsImportError",
        
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
                params: {id:  this.$route.params.id} 
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
        }
      },
      testFileDelete(){
        this.testFile = ''
        this.testValue = null
      }
    },
    async mounted(){},
    async created(){
      console.log(this.$route)
      console.log(this.parentId)
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
  .form-import{
    margin-bottom: 15px;
  }
  .question-item{
    margin-top: 10px;
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
    padding: 5px 10px;
    transition: all 0.1s ease-out;
    &:hover{
      background-color: rgb(156, 156, 154);
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
 @media (min-width: 1024px) {
  
 }
</style>