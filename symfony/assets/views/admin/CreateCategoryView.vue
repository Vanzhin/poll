<template>
  <div class="block">
    <div class="title">
      <h2>Форма редактирования категорий</h2>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <form @submit.prevent="onSubmit">
        <input type="hidden" 
          name="parentId" 
          :value="parentId"
          v-if="parentId"
        >
        <p class="label"><b>Категория: </b></p>
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
        <label class="label"><b>Описание действий:</b> </label>
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
        <div class="mb-3 w-100">
          <div class="img_block"
            v-if="typeof imageFile === 'object'"
          >
            <img :src="imageUrl"  width="200"/> 
            <i class="bi bi-x-lg custom-close" title="Удалить изображение"
              @click.stop="imgDelete(ind)"
            ></i>
          </div> 
          <div class="img_block"
            v-else-if="image"
          >
            <img :src="image"  width="200"/> 
            <i class="bi bi-x-lg custom-close" title="Удалить изображение"
              @click.stop="image = null"
            ></i>
          </div> 
          <label class="label"
            v-if="typeof imageFile === 'object' || image"
          >Изменить изображение </label>
          <label class="label"
            v-else
          >Прикрепить изображение </label>
          <input  class="" type="file" accept="image/*"  
            @change="(e)=> changeImg(e)"
            name="categoryImage"
            :value="imageValue"
          >
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
        image: "",
        imageFile: "",
        imageUrl: "",
        imageValue: "",
        message: null
      }
    },
    computed:{ 
      ...mapGetters(["getAutchUserToken", "getMessage", ]),
      getCategory () {
        const category = this.$store.getters.getCategory( +this.$route.params.id)
        console.log(category)
        return category
      },
    },
   
    methods: { 
      ...mapActions(["editCategory", "createCategory" ,"setMessage"]),
      ...mapMutations([]),
      setSelectTypeQuestion(){},
      onSubmit(e){
       
        // if (!trueAnswer[0].value) {
        //   const message = {
        //     err: true, 
        //     mes: 'Укажите правильный ответ!'
        //   }
        //   this.setMessage(message)
        //   return
        // }
        const questionSend = e.target
        // this.setMessage({rrr:"dfgdfg"})
        if ( this.$route.params.operation === 'edit'){

          this.editCategory({questionSend, token: this.getAutchUserToken, id:+this.$route.params.id})
          return
        }
        if ( this.$route.params.operation === 'create'){
          this.createCategory({questionSend, token: this.getAutchUserToken, id:+this.$route.params.id})
          return
        }
        // this.$router.push({ path:'/result'})
      },
      
      changeImg(e){
        if (typeof e.target.files[0] === 'object'){
          this.imageFile = e.target.files[0]
          this.imageUrl = URL.createObjectURL(e.target.files[0])
          this.imageValue = e.target.value
        }
      },
      imgDelete(ind){
        this.imageFile = ''
        this.imageUrl = ''
        this.imageValue = ''
      },
    },
    async mounted(){
      
    },
    async created() {
      if ( this.$route.params.operation === 'edit'){
        // this.parentId = this.$route.params.id
        this.title = this.getCategory.title
        this.description = this.getCategory.description
        this.image = this.getCategory.image
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