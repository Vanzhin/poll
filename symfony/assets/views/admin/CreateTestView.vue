<template>
  
  <div class="block">
    <div class="title">
      <h2>Форма редактирования тестов</h2>
    </div>
    
  </div>
  <Loader
    v-if="isLoader"
  />
  <div class="container"
    v-else
  >
    <Button/>
    <div class="row">
      <form @submit.prevent="onSubmit">
        <input type="hidden" 
          name="category" 
          :value="parentId"
        >
       
        <div class="custom-radio mintrud">  
          <p class="label"><b>Тест: </b></p>
          <input type="checkbox"
            v-model="checkedMinTrud"
          >
          <b> Минтруд </b>
        </div>
        <div class="mintrud_block"
          v-if="checkedMinTrud"
        >
          <input type="hidden" 
            name="minTrud" 
            :value="selectTestMinTrud"
          >
          <input type="hidden" 
            name="title" 
            :value="title"
          >
          <p class="mintrud_title"
            
          >{{ title }}</p>
          <div class="dropdown trud_select"> 
            <button class=" dropdown-toggle trud_select" 
              id="dropdownMenuButton1" 
              data-bs-toggle="dropdown" 
              aria-expanded="false">
              Выберите тест
            </button> {{ selectTestMinTrud }} 
            <ul class="dropdown-menu trud_grup_option" aria-labelledby="dropdownMenuButton1">
              <li class="trud_option"
                v-for="(item) in getTestsMinTrud"
                :key="item.id"
                :value="item.id"
                @click="selectedTestMinTrud(item)"
              >{{ item.title }}</li>
              
            </ul>
          </div>
          
        </div>
        <div class="custom-radio img_block"
          v-else
        >
          <textarea rows="2" required
            name="title"
            v-model= "title"
            class="textarea_input" 
          ></textarea> 
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="title = ''"
            v-if="title !== ''"
          ></i>
        </div>
        <label class="label"><b>Описание:</b> </label>
        <div class="custom-radio img_block">  
          <textarea rows="1" 
            name="description"
            v-model= "description"
            class="textarea_input" 
          ></textarea> 
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="description = ''"
            v-if="description !== ''"
          ></i>
        </div>
        <label class="label"><b>Заголовок для крошек:</b> </label>
        <div class="custom-radio img_block">  
          <input rows="1" 
            name="alias"
            v-model= "alias"
            class="input_alias" 
          >
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="alias = ''"
            v-if="alias !== ''"
          ></i>
        </div>
        <label class="label"><b>Дополнительное описание для SEO:</b> </label>
        <div class="custom-radio img_block">  
          <textarea rows="1" 
            name="descriptionSeo"
            v-model= "descriptionSeo"
            class="textarea_input" 
          ></textarea> 
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="descriptionSeo = ''"
            v-if="descriptionSeo !== ''"
          ></i>
        </div>
        <label class="label"><b>Canonical:</b> </label>
        <div class="custom-radio img_block">  
          <input rows="1" 
            name="canonical"
            v-model= "canonical"
            class="input_alias" 
          >
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="canonical = ''"
            v-if="canonical !== ''"
          ></i>
        </div>
        <div  class="checkbox_label">
          <input type="hidden" 
          name="robots" 
          :value="robotsText"
        >
          <label class="label"><b>Robots:</b>{{ robots }} </label>
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="robots = []"
            v-if="robots.length > 0"
          ></i>
        </div>
        <div class="">  
          <div class="checkbox_label"
            v-for="item in robotsEven"
            :key="item.name"
            :title="item.title"
          >
            <input type="checkbox" 
              :value= "item.name"
              v-model="robots"
              class="custom-control-input"  
            > 
            <label>{{ item.name }}</label>
          </div>
          
        </div>
        <label class="label"><b>Укажите время на прохождение теста:</b> </label>
        <div class="custom-radio img_block">  
          <input  
            name="time"
            v-model= "time"
            class="textarea_input" 
            required
            pattern="[0-9]{1,10}"
          > 
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="time = ''"
            v-if="time !== ''"
          ></i>
        </div>
        <label class="label"><b>Укажите количество секций для  прохождения теста:</b> </label>
        <div class="custom-radio img_block">  
          <input  
            name="sectionCountToPass"
            v-model= "sectionCountToPass"
            class="textarea_input" 
            required
            pattern="[0-9]{1,10}"
          > 
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="sectionCountToPass = ''"
            v-if="sectionCountToPass !== ''"
          ></i>
        </div>
        <br> 
          
        <button type="submit" class="button">Сохранить</button>
      </form>
    </div>
  </div>
</template>
 
<script>
  import MessageView from "../../components/ui/MessageView.vue"
  import Loader from '../../components/ui/Loader.vue'
  import Button from "../../components/ui/ButtonBack.vue"
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      MessageView,
      Loader,
      Button
    },
    data() {
      return {
        robotsEven:[
          {name:'noindex', title: 'Не индексировать текст страницы. Страница не будет участвовать в результатах поиска.'}, 
          {name:'nofollow', title: 'Не переходить по ссылкам на странице. Робот не перейдет по ссылкам при обходе сайта, но может узнать о них из других источников. Например, на других страницах или сайтах.'}, 
          {name:'none', title: 'Соответствует директивам noindex, nofollow.'}, 
          {name:'noarchive', title: 'Не показывать ссылку на сохраненную копию в результатах поиска.'}, 
          {name:'noyaca', title: 'Не использовать сформированное автоматически описание.'}, 
          {name:'all', title: 'Соответствует директивам index и follow — разрешено индексировать текст и ссылки на странице.'}
        ],
        typeQuestions:[
          {id: 1, 
            type: "radio",
            title: "Один из многих",
          },
        ],
        selectTypeQuestion: '',
        parentId: undefined,
        title: "",
        description: "",
        alias: "",
        time: 1200,
        sectionCountToPass: "",
        message: null,
        isLoader: true,
        operation: this.$route.params.operation,
        checkedMinTrud: false,
        selectTestMinTrud: null,
        canonical: "",
        robots: [],
        descriptionSeo: "",
      }
    },
    computed:{ 
      ...mapGetters([
        "getAutchUserToken", 
        "getMessage", 
        "getCategoryParendId", 
        "getTest",
        "getTestsMinTrud",

      ]),
      getTest () {
        const test = this.$store.getters.getTest
        console.log(test)
        return test
      },
      robotsText(){
        console.log(this.robots.toString())
        return this.robots.toString()
      }
    },
   
    methods: { 
      ...mapActions([
        "editTest", 
        "createTest",
        "setMessage", 
        "selectTestId", 
        "getTestIdDb",
        "getCountQuestionsTest",
        "getTestsMinTrudDb"
      ]),
      ...mapMutations([]),
      setSelectTypeQuestion(){},
      async onSubmit(e){
        const questionSend = e.target
        
        if ( this.operation === 'edit'){
          await this.editTest({questionSend, token: this.getAutchUserToken, id:+this.$route.params.id})
          
        } else if ( this.operation === 'create'){
          await this.createTest({questionSend, token: this.getAutchUserToken, })
         
        }
        
        this.message = !this.getMessage.err
        
        let timerId = setInterval(() => {
          if ( !this.getMessage) {
            clearInterval(timerId)
            if (this.message ){this.$router.go(-1)}
          }
        }, 200);
      },
      selectedTestMinTrud(item) {
        this.selectTestMinTrud = item.id
        this.title = item.title
      },
    },

    async mounted(){
      
    },
    async created() {
      if ( this.$route.params.operation === 'create'){
        this.parentId = this.$route.params.id
      } 
      
      
      if ( this.$route.params.operation === 'edit'){
        // await this.selectTestId({id: +this.$route.params.id})
        await this.getTestIdDb({id: +this.$route.params.id})
        this.title = this.getTest.title || ''
        this.description = this.getTest.description || ''
        this.descriptionSeo = this.getTest.descriptionSeo || ''
        this.canonical = this.getTest.canonical || ''
        this.robots = this.getTest.robots ? this.getTest.robots.split(',') : []
        this.sectionCountToPass = this.getTest.sectionCount || ''
        this.alias = this.getTest.alias || ''
        this.time = this.getTest.time || ''
      }
      console.log(this.getTestsMinTrud)
      if (!this.getTestsMinTrud) {
        console.log('запрос тестов минтруд')
        await this.getTestsMinTrudDb()
      }
      this.isLoader = false
      
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
  .label{
    display:block;
    margin: 0 10px;
  }
  .checkbox_label{
    display: flex;
    align-items: center;
    & label {
      margin-left: 10px;
    }
  }
  .textarea_input{
    max-width: 50%;
    padding: 0;
    padding-left: 10px;
    margin: 5px;
  }
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
  .mintrud{
    display: flex;
    align-items: center;
    &_block{
      display: flex;
      flex-direction: column;
    }
    &_title{
      background-color: #fff;
      border: var(--border) solid var(--color-secondary);
      border-radius: var(--radius);
      padding: 10px;
      min-height: 50px;
      max-width: 70%;
    }
    & b {
      margin-left: 10px;
    }
  }
  .trud{
    &_select{
      width: 80%;
    }
  
    &_grup_option{
      max-height: 50vh;
      overflow-y: auto;
    }
    &_option{
    
      &:hover{
        background-color: rgb(170, 185, 197);
        cursor: pointer;
      }
    }
  }
  .input_alias{
    width: 22%;
    border: 1px solid rgb(235 235 232);
    margin: 5px;
  }
 @media (min-width: 1024px) {
  
 }
</style>