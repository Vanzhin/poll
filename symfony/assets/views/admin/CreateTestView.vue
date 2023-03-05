<template>
  <div class="block">
    <div class="title">
      <h2>Форма редактирования тестов</h2>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <form @submit.prevent="onSubmit">
        <input type="hidden" 
          name="category" 
          :value="parentId"
        >
        <p class="label"><b>Тест: </b></p>
        <div class="custom-radio img_block">
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
          <div style="width: 100%;">
            <MessageView/>
          </div>
        <button type="submit" class="button">Сохранить</button>
      </form>
    </div>
  </div>
</template>
 
<script>
  import MessageView from "../../components/ui/MessageView.vue"
  
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      
      MessageView
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
        parentId: undefined,
        title: "",
        description: "",
        message: null
      }
    },
    computed:{ 
      ...mapGetters(["getAutchUserToken", "getMessage", "getCategoryParendId"]),
      getTest () {
        const test = this.$store.getters.getTest( +this.$route.params.id)
        console.log(test)
        return test
      },
    },
   
    methods: { 
      ...mapActions(["editTest", "createTest" ,"setMessage"]),
      ...mapMutations([]),
      setSelectTypeQuestion(){},
      async onSubmit(e){
        const questionSend = e.target
        
        if ( this.$route.params.operation === 'edit'){
          await this.editTest({questionSend, token: this.getAutchUserToken, id:+this.$route.params.id})
          
        } else if ( this.$route.params.operation === 'create'){
          await this.createTest({questionSend, token: this.getAutchUserToken, })
         
        }
        let timerId = setInterval(() => {
          if ( !this.getMessage) {
            clearInterval(timerId)
            this.$router.go(-1)
          }
        }, 200);
      
      },
    },
    async mounted(){
      
    },
    async created() {
      if ( this.$route.params.operation === 'create'){
        this.parentId = this.$route.params.id
      } 
      
      
      if ( this.$route.params.operation === 'edit'){
        this.title = this.getTest.title
        this.description = this.getTest.description
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