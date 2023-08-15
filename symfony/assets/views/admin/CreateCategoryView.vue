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
        <p class="label"><b>Категория (title, h1): </b></p>
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
        <label class="label"><b>Описание (description):</b> </label>
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
        <div class="mb-3 w-100 mt-3">
          <div class="img_block"
            v-if="imageUrl !== ''"
          >
            <img :src="imageUrl"  width="200"/> 
            <i class="bi bi-x-lg custom-close" title="Удалить изображение"
              @click.stop="imgDelete()"
            ></i>
          </div> 
          
          <label class="label"
            v-if="imageUrl !== ''"
          >Изменить изображение </label>
          <label class="label"
            v-else
          >Прикрепить изображение </label>
          <input  class="" type="file" accept="image/*"  
            @change="(e)=> changeImg(e)"
            :name="(imageUrl === '') && (imageValue  === '')? '' : 'categoryImage'"
            :value="imageValue"
          >
        </div>
        <br> 
          
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
        robotsEven:[
          {name:'noindex', title: 'Не индексировать текст страницы. Страница не будет участвовать в результатах поиска.'}, 
          {name:'nofollow', title: 'Не переходить по ссылкам на странице. Робот не перейдет по ссылкам при обходе сайта, но может узнать о них из других источников. Например, на других страницах или сайтах.'}, 
          {name:'none', title: 'Соответствует директивам noindex, nofollow.'}, 
          {name:'noarchive', title: 'Не показывать ссылку на сохраненную копию в результатах поиска.'}, 
          {name:'noyaca', title: 'Не использовать сформированное автоматически описание.'}, 
          {name:'all', title: 'Соответствует директивам index и follow — разрешено индексировать текст и ссылки на странице.'}],
        parentId: undefined,
        title: "",
        description: "",
        descriptionSeo: "",
        imageFile: "",
        imageUrl: "",
        imageValue: "",
        alias:'',
        canonical: "",
        robots: [],
        message: null
      }
    },
    computed:{ 
      ...mapGetters(["getAutchUserToken", "getMessage", "getCategoryParendId"]),
      getCategory () {
        const category = this.$store.getters.getCategory( +this.$route.params.id)
        console.log(category)
        return category
      },
      robotsText(){
        console.log(this.robots.toString())
        return this.robots.toString()
      }
    },
   
    methods: { 
      ...mapActions(["editCategory", "createCategory" ,"setMessage"]),
      ...mapMutations([]),
      setSelectTypeQuestion(){},
      async onSubmit(e){
        const questionSend = e.target
        
        if ( this.$route.params.operation === 'edit'){
          await this.editCategory({questionSend, token: this.getAutchUserToken, id:+this.$route.params.id})
        } else if ( this.$route.params.operation === 'create'){
          await this.createCategory({questionSend, token: this.getAutchUserToken, id:+this.$route.params.id})
        }
        this.message = !this.getMessage.err
        let timerId = setInterval(() => {
          if ( !this.getMessage ) {
            clearInterval(timerId)
            if (this.message ){this.$router.go(-1)}
          }
        }, 200);
        
        // this.$router.push({ path:'/result'})
      },
      
      changeImg(e){
        if (typeof e.target.files[0] === 'object'){
          this.imageUrl = URL.createObjectURL(e.target.files[0])
          this.imageValue = e.target.value
        }
      },
      imgDelete(ind){
        this.imageUrl = ''
        this.imageValue = ''
      },
    },
    async mounted(){
      
    },
    async created() {
      if ( this.$route.params.operation === 'create'){
        if (this.$route.params.id > 0) {
          this.parentId = this.$route.params.id 
        }
      }
      if ( this.$route.params.operation === 'edit'){
        // this.parentId = this.$route.params.id
        this.title = this.getCategory.title
        this.description = this.getCategory.description ? this.getCategory.description : ''
        this.imageUrl = this.getCategory.image ? this.getCategory.image : ''
        this.alias = this.getCategory.alias ? this.getCategory.alias : ''
        this.descriptionSeo = this.getCategory.descriptionSeo ? this.getCategory.descriptionSeo : ''
        this.canonical = this.getCategory.canonical ? this.getCategory.canonical : ''
        this.robots = this.getCategory.robots ? this.getCategory.robots.split(',') : []
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
  .input_alias{
    width: 50%;
    border-color: rgb(243 243 238);
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
  .custom-control {
    position: relative;
    display: flex;
    align-items:flex-start;
    min-height: 1.5rem;
    padding-left: 1.5rem;
    margin-top: 5px;
    align-items: center;
  }
  .custom-control-label {
      position: relative;
      margin-bottom: 0;
      margin-left: 10px;
      word-wrap: break-word;
  }
 @media (min-width: 1024px) {
  
 }
</style>