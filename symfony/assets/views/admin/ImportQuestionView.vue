<template>
   <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <h2>Импортировать тест из файла</h2>
    </div>
    <hr/>
   <div class="container">
      <div class="row">
        <form @submit.prevent="onSubmit">
         
          <label class="label">Выберите файл для импорта</label>
          <br>
          <div class="d-flex align-items-center">
            <input  class="input_file" type="file"  
              @change="changeFileValue" 
              name="file_title"
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
          <div style="width: 100%;">
            <MessageView/>
          </div>
          <button type="submit" class="button">Загрузить</button>
        </form>
      </div>
    </div>
  </div>

  
</template>
 
<script>
  import MessageView from "../../components/ui/MessageView.vue"
  import Loader from '../../components/ui/Loader.vue'
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      MessageView
    },
    data() {
      return {
        testFile:'',
        testValue:null,
        testFileUrl:'',
        isLoader: true,
      }
    },
    computed:{ 
      ...mapGetters(["getAutchUserToken", "getMessage"]),
    },
   
    methods: { 
      ...mapActions(["importFileTestDb", "setMessage"]),
      ...mapMutations([]),
      onSubmit(e){
       
        if (!this.testValue) {
          const message = {
            err: true, 
            mes: 'Выберите файл!'
          }
          this.setMessage(message)
          return
        }
        const testFile = e.target
        // this.setMessage({rrr:"dfgdfg"})
        this.importFileTestDb({testFile, token: this.getAutchUserToken})
        // this.$router.push({ path:'/result'})
      },
      changeFileValue(e){
        if (typeof e.target.files[0] === 'object'){
          this.testFile = e.target.files[0]
          this.testFileUrl = URL.createObjectURL(e.target.files[0])
          this.testValue = e.target.value
        }
      },
      testFileDelete(){
        this.testFile = ''
        this.testFileUrl = ''
        this.testValue = null
      }
    },
    async mounted(){
      setTimeout(()=>this.isLoader = false, 1000 )
      
    }
   
 } 
 
</script>
<style lang="scss" scoped>
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