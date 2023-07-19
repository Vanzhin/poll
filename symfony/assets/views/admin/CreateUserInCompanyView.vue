<template>
  <div class="block">
    <div class="title">
      <h2>Форма редактирования пользователей</h2>
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
             
        <div
          
        >
          <div class="grup_label">
            <div>
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
            <div>
              <label class="label"><b>Логин:</b> </label>
              <div class="custom-radio img_block">  
                <input  
                  name="login"
                  v-model= "login"
                  class="input_alias" 
                >
                <i class="bi bi-eraser custom-close" title="Очистить поле"
                  @click="login = ''"
                  v-if="login !== ''"
                ></i>
              </div>
            </div>
          </div>
          <div class="grup_label">
            <div>
              <label class="label"><b>Имя:</b> </label>
              <div class="custom-radio img_block">  
                <input  
                  name="login"
                  v-model= "profile.firstName"
                  class="input_alias" 
                >
                <i class="bi bi-eraser custom-close" title="Очистить поле"
                  @click="profile.firstName = ''"
                  v-if="profile.firstName !== ''"
                ></i>
              </div>
            </div>
            <div>
              <label class="label"><b>Фамилия:</b> </label>
              <div class="custom-radio img_block">  
                <input  
                  name="login"
                  v-model= "profile.lastName"
                  class="input_alias" 
                >
                <i class="bi bi-eraser custom-close" title="Очистить поле"
                  @click="profile.lastName = ''"
                  v-if="profile.lastName !== ''"
                ></i>
              </div>
            </div>
            <div>
              <label class="label"><b>Отчество:</b> </label>
              <div class="custom-radio img_block">  
                <input  
                  name="login"
                  v-model= "profile.middleName"
                  class="input_alias" 
                >
                <i class="bi bi-eraser custom-close" title="Очистить поле"
                  @click="profile.middleName = ''"
                  v-if="profile.middleName !== ''"
                ></i>
              </div>
            </div>
          </div>
          <label class="label"><b>Гражданство:</b> </label>
          <div class="custom-radio img_block">  
            <input  
              name="login"
              v-model= "profile.citizenship"
              class="input_alias" 
            >
            <i class="bi bi-eraser custom-close" title="Очистить поле"
              @click="profile.citizenship = ''"
              v-if="profile.citizenship !== ''"
            ></i>
          </div>
          <label class="label"><b>Диплом:</b> </label>
          <div class="custom-radio img_block">  
            <input  
              name="login"
              v-model= "profile.diploma"
              class="input_alias" 
            >
            <i class="bi bi-eraser custom-close" title="Очистить поле"
              @click="profile.diploma = ''"
              v-if="profile.diploma !== ''"
            ></i>
          </div>
          <label class="label"><b>Образование:</b> </label>
          <div class="custom-radio img_block">  
            <input  
              name="login"
              v-model= "profile.educationLevel"
              class="input_alias" 
            >
            <i class="bi bi-eraser custom-close" title="Очистить поле"
              @click="profile.educationLevel = ''"
              v-if="profile.educationLevel !== ''"
            ></i>
          </div>
          <label class="label"><b>СНИЛС:</b> </label>
          <div class="custom-radio img_block">  
            <input  
              name="login"
              type="text" 
              placeholder="123-235-122 12"
              v-model= "profile.snils"
              class="input_alias" 
              pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}( |-)[0-9]{2}"
            >
            <i class="bi bi-eraser custom-close" title="Очистить поле"
              @click="profile.snils = ''"
              v-if="profile.snils !== ''"
            ></i>
          </div>
          <div class="grup_label">
            <div>
              <label class="label"><b>Отдел:</b> </label>
              <div class="custom-radio img_block">  
                <input  
                  name="login"
                  v-model= "profile.department"
                  class="input_alias" 
                >
                <i class="bi bi-eraser custom-close" title="Очистить поле"
                  @click="profile.department = ''"
                  v-if="profile.department !== ''"
                ></i>
              </div>
            </div>
            <div>
              <label class="label"><b>Должность:</b> </label>
              <div class="custom-radio img_block">  
                <input  
                  name="login"
                  v-model= "profile.position"
                  class="input_alias" 
                >
                <i class="bi bi-eraser custom-close" title="Очистить поле"
                  @click="profile.position = ''"
                  v-if="profile.position !== ''"
                ></i>
              </div>
            </div>
          </div>
          <div class="grup_label">
            <div>
              <label class="label"><b>Пароль:</b> </label>
              <div class="custom-radio img_block">  
                <input  
                  name="login"
                  v-model= "password"
                  class="input_alias" 
                >
                <i class="bi bi-eraser custom-close" title="Очистить поле"
                  @click="password = ''"
                  v-if="password !== ''"
                ></i>
              </div>
            </div>
            <div>
              <label class="label"><b>Повторите пароль:</b> </label>
              <div class="custom-radio img_block">  
                <input  
                  name="login"
                  v-model= "confirmPassword"
                  class="input_alias" 
                >
                <i class="bi bi-eraser custom-close" title="Очистить поле"
                  @click="confirmPassword = ''"
                  v-if="confirmPassword !== ''"
                ></i>
              </div>
            </div>
          </div>
          <div>
              <label class="label"><b>Доступ:</b> </label>
              <div class="custom-radio img_block">  
                <select 
                  v-model="roles"
                >
                  <option disabled value="">Выберите один из вариантов</option>
                  <option 
                    v-for="(variant ) in rolesTitle"
                    :key="variant"
                    :value="variant.value" 
                  >
                    {{ variant.value }}
                  </option>
                </select>
                {{ roles }}
              </div>
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
        userId:'',
        email:'',
        login: '',
        profile: {
          firstName: "",
          middleName: "",
          lastName: "",
          position: "",
          department: "",
          snils: "",
          diploma: "",
          citizenship: "",
          educationLevel: ""
        },
        roles: 'Пользователь',
        isActive: true,
        password: '',
        confirmPassword: "",

        message: null,
        companyId: null,
        operationCreate: true,
        rolesTitle:[
          {value:'Пользователь'},
          {value:'Наставник'},
        ]
      }
    },
    computed:{ 
      ...mapGetters([
        "getAutchUserToken", 
        "getMessage", 
        "getCompany",
        "getUserCompanyEdit"
      ]),
      
    },
   
    methods: { 
      ...mapActions([
        "createUserInCompanyDb",
        "setMessage",
        "getCompanyIdDB",

      ]),
      ...mapMutations([]),
      
      async onSubmit(e){
        const conpanySend = e.target
        const user ={
            email: this.email,
            login: this.login,
            profile: this.profile,
            roles: [this.roles],
            isActive: this.isActive,
            password:  this.password,
            confirmPassword: this.confirmPassword,
          }
        if ( this.$route.params.operation === 'edit'){
          await this.createUserInCompanyDb({user, userId:this.userId})
        } else if ( this.$route.params.operation === 'create'){
          
          await this.createUserInCompanyDb({user})
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
        const user = this.getUserCompanyEdit
        // this.companyId = +this.$route.params.id
        // if (!this.getCompany.id  ) {
        //   await this.getCompanyIdDB({id: this.companyId})
        // }
        this.userId=user.id
        this.email=user.email
        this.login=user.login
        this.profile= user.profile
        this.roles=user.roles[0]
        this.isActive=user.isActive
        this.password=user.password
        this.confirmPassword=user.confirmPassword
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
    min-width: 220px;
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
  .grup_label{
    display: flex;
    justify-content: space-between;
  }
 @media (min-width: 1024px) {
  
 }
</style>