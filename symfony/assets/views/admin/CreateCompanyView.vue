<template>
  <div class="block">
    <div class="title">
      <h2>Форма редактирования компаний</h2>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <form @submit.prevent="onSubmit">
        <input type="hidden" 
          name="companyId" 
          :value="companyId"
          v-if="companyId"
        >
        <p class="label"><b>Название: </b></p>
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
        <label class="label"><b>ИНН:</b> </label>
        <div class="custom-radio img_block">  
          <input  
            name="tin"
            v-model= "tin"
            class="input_alias"
            required 
            pattern="[0-9]{10}"
            placeholder="1234567891"
          />
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="tin = ''"
            v-if="tin !== ''"
          ></i>
        </div>
        <div
          v-if="operationCreate"
        >
          <label class="label"><b>E-mail:</b> </label>
          <div class="custom-radio img_block">  
            <input  
              name="email"
              v-model= "email"
              class="input_alias" 
            >
            <i class="bi bi-eraser custom-close" title="Очистить поле"
              @click="email = ''"
              v-if="email !== ''"
            ></i>
          </div>
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
        title: "",
        tin: "",
        email:'',
        message: null,
        companyId: null,
        operationCreate: true
      }
    },
    computed:{ 
      ...mapGetters(["getAutchUserToken", "getMessage", "getCompany"]),
      
    },
   
    methods: { 
      ...mapActions([
        "editCompanyDb", 
        "createCompanyDb",
        "setMessage",
        "getCompanyIdDB"]),
      ...mapMutations([]),
      
      async onSubmit(e){
        const conpanySend = e.target
        
        if ( this.$route.params.operation === 'edit'){
          await this.editCompanyDb({conpanySend, id: this.companyId})
        } else if ( this.$route.params.operation === 'create'){
          await this.createCompanyDb({conpanySend})
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
      
      
    },
    async mounted(){
      
    },
    async created() {
      if ( this.$route.params.operation === 'create'){
        if (this.$route.params.id > 0) {
          this.companyId = this.$route.params.id 
        }
      }
      if ( this.$route.params.operation === 'edit'){
        this.operationCreate = false
        this.companyId = +this.$route.params.id
        if (!this.getCompany.id  ) {
          await this.getCompanyIdDB({id: this.companyId})
        }
        this.title = this.getCompany.title ? this.getCompany.title : ''
        this.tin = this.getCompany.tin ? this.getCompany.tin : ''
        this.email = this.getCompany.email ? this.getCompany.email : ''
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
  .input_alias{
    width: 22%;
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
 @media (min-width: 1024px) {
  
 }
</style>