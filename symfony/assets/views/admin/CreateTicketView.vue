<template>
  
  <div class="block">
    <div class="title">
      <h2>Форма редактирования билетов</h2>
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
        <p class="label"><b>Билет: </b></p>
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
        <div class="block_number">
          <label for="number" class="label"> Количество ответов</label>
          <input id="number" type="number"
            v-model="numberQuestions"
            @change="changeNumberQuestions"
          >
        </div>
        <div class="custom-radio img_block">  
          <textarea rows="1" required
            name="description"
            v-model= "description"
            class="textarea_input" 
          ></textarea> 
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="description = ''"
            v-if="description !== ''"
          ></i>
        </div>
        <br> 
          
        <button type="submit" class="button">Сохранить</button>
      </form>
    </div>
  </div>
</template>
 
<script>
  import Loader from '../../components/ui/Loader.vue'
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
    },
    data() {
      return {
        typeQuestions:[
          {id: 1, 
            type: "radio",
            title: "Один из многих",
          },
        ],
        selectTypeQuestion: '',
        testId: undefined,
        title: "",
        description: "",
        message: null,
        isLoader: true,
        operation: this.$route.params.operation,
        numberQuestions: 10,
        questions:[]
      }
    },
    computed:{ 
      ...mapGetters([
        "getAutchUserToken", 
        "getMessage", 
        "getCategoryParendId", 
        "getTest",
        
        "getTickets"
    ]),
      getTest () {
        const test = this.$store.getters.getTest
        console.log(test)
        return test
      },
    },
   
    methods: { 
      ...mapActions(["editTest", "createTest" ,"setMessage", "selectTestId", "getTestIdDb"]),
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
      changeNumberQuestions(){
        if (this.numberQuestions < 1) {
        this.numberQuestions = 1
        return
      }
      if ( this.questions.length < this.numberQuestions) {
        ++this.uniqueNumber
        // this.questions.push({id:'a' + (this.uniqueNumber), title:"", image:"", value:""})
        
      } else {
        // const arr = this.answerSelect.filter(item =>{ 
        //  return item !== this.answers[this.answers.length - 1].id})
        // this.answerSelect = arr
        // this.answers.pop()
      }
      }
    },
    async mounted(){
      
    },
    async created() {
      if ( this.$route.params.operation === 'create'){
        this.testId = this.$route.params.id
        this.title = `Билет № ${this.getTickets.length + 1}`
      } 
      
      
      // if ( this.$route.params.operation === 'edit'){
      //   // await this.selectTestId({id: +this.$route.params.id})
      //   await this.getTestIdDb({id: +this.$route.params.id})
      //   this.title = this.getTest.title
      //   this.description = this.getTest.description
      // }
      this.isLoader = false
      
    }
   
 } 
 
</script>
<style lang="scss" scoped>
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
 @media (min-width: 1024px) {
  
 }
</style>