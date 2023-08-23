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
            <div class="grup_coll">
              <div 
                v-for="(item) in user"
                :key="item"
                >
                <div
                  v-if="item.visible"
                >
                  <label class="label"><b>{{  item.title}}</b> </label>
                  <div class="custom-radio img_block">  
                    <input  
                      name="email"
                      v-model= "item.value"
                      class="input_alias" 
                    >
                    <i class="bi bi-eraser custom-close" title="Очистить поле"
                      @click="item.value = ''"
                      v-if="item.value !== ''"
                    ></i>
                  </div>
                </div>
              </div>
              
              
              <div class="toogge_button"
                v-if="tooggePaswordVisible"
                @click="tooggePasword"
              >Изменить пароль - {{  tooggePaswordTitle }}
              </div>
            </div>
            <div class="grup_coll">
              <div 
                v-for="(prof) in profile"
                :key="prof"
                >
                <div
                  v-if="prof.visible"
                >
                  <label class="label"><b>{{  prof.title}}</b> </label>
                  <div class="custom-radio img_block">  
                    <input  
                      name="email"
                      v-model= "prof.value"
                      class="input_alias" 
                    >
                    <i class="bi bi-eraser custom-close" title="Очистить поле"
                      @click="prof.value = ''"
                      v-if="prof.value !== ''"
                    ></i>
                  </div>
                </div>
              </div>
              
            </div>
          </div>

         
          <div>
                <label class="label"><b>Образование:</b> </label>
                <div class="custom-radio img_block">  
                  <select 
                    v-model="educationLevel"
                  >
                    <option disabled value="">Выберите один из вариантов</option>
                    <option 
                      v-for="(variant ) in getUserListEducationLevel"
                      :key="variant"
                      :value="variant" 
                    >
                      {{ variant }}
                    </option>
                  </select>
                  
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
                  v-for="(role ) in rolesTitle"
                  :key="role.value"
                  :value="role.value" 
                >
                {{ role.value }}
                </option>
              </select>
              
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
        user: {
          userId:{value: '', title:'',visible: false},
          email:{value: '', title:'E-mail:',visible: true},
          login: {value: '', title:'Логин:',visible: true},
          isActive: {value: true, title:'',visible: false},
          password:{value: '', title:'Пароль:',visible: true},
          confirmPassword: {value:'', title:'Повторите пароль:',visible: true},
        },
        
        profile: {
          firstName: {value: '', title:'Имя:',visible: true},
          middleName: {value: '', title:'Фамилия:',visible: true},
          lastName: {value: '', title:'Отчество:',visible: true},
          position: {value: '', title:'Должность:',visible: true},
          department: {value: '', title:'Отдел:',visible: true},
          snils: {value: '', title:'СНИЛС:',visible: true},
          diploma: {value: '', title:'Диплом:',visible: true},
          citizenship: {value: '', title:'Гражданство:',visible: true},
         
        },
        roles: 'Пользователь',
        educationLevel:"",
        message: null,
        companyId: null,
        operationCreate: true,
        rolesTitle:[
          {value:'Пользователь'},
          {value:'Наставник'},
        ],
        tooggePaswordTitle: "да",
        tooggePaswordVisible: false
      }
    },
    computed:{ 
      ...mapGetters([
        "getAutchUserToken", 
        "getMessage", 
        "getCompany",
        "getUserCompanyEdit",
        "getUserListEducationLevel"
      ]),
      
    },
   
    methods: { 
      ...mapActions([
        "createUserInCompanyDb",
        "setMessage",
        "getCompanyIdDB",
        "getUserListEducationLevelDb"
      ]),
      ...mapMutations([]),
      
      async onSubmit(e){
        const conpanySend = e.target
        const user = {
            email: this.user.email.value,
            login: this.user.login.value,
            isActive: this.user.isActive.value,
            roles: [this.roles],
            profile: {
              firstName:  this.profile.firstName.value,
              middleName:  this.profile.middleName.value,
              lastName:  this.profile.lastName.value,
              position:  this.profile.position.value,
              department:  this.profile.department.value,
              snils:  this.profile.snils.value,
              diploma: this.profile.diploma.value,
              citizenship:  this.profile.citizenship.value,
              educationLevel:  this.educationLevel
            },
          } 
          if (this.user.password.visible){
            user.password = this.user.password.value
            user.confirmPassword = this.user.confirmPassword.value
          }
          
        if ( this.$route.params.operation === 'edit'){
          await this.createUserInCompanyDb({user, userId:this.$route.params.userId})
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
      tooggePasword(){
        this.user.password.visible = !this.user.password.visible
        this.user.confirmPassword.visible  = !this.user.confirmPassword.visible
        this.tooggePaswordTitle = this.user.confirmPassword.visible ? "нет" : "да"
      }
      
      
    },
    async mounted(){
      
    },
    async created() {
      if (this.getUserListEducationLevel) {

      } else {
        console.log("cgbcjr")
        await this.getUserListEducationLevelDb({})
      }
      if ( this.$route.params.operation === 'create'){
        if (this.$route.params.id > 0) {
          this.companyId = this.$route.params.id 
        }
      }
      if ( this.$route.params.operation === 'edit'){
        this.operationCreate = false
        const getuser = this.getUserCompanyEdit
        // this.companyId = +this.$route.params.id
        // if (!this.getCompany.id  ) {
        //   await this.getCompanyIdDB({id: this.companyId})
        // }
        this.tooggePaswordVisible = true
        this.user.userId.value = getuser.id
        this.user.email.value = getuser.email
        this.user.login.value = getuser.login
        this.user.isActive.value = getuser.isActive
        this.user.password.value = getuser.password || ''
        this.user.confirmPassword.value = getuser.confirmPassword || ''
        this.user.password.visible = false
        this.user.confirmPassword.visible = false

        this.profile.firstName.value = getuser.profile.firstName,
        this.profile.middleName.value = getuser.profile.middleName,
        this.profile.lastName.value = getuser.profile.lastName,
        this.profile.position.value = getuser.profile.position,
        this.profile.department.value = getuser.profile.department,
        this.profile.snils.value = getuser.profile.snils,
        this.profile.diploma.value = getuser.profile.diploma,
        this.profile.citizenship.value = getuser.profile.citizenship,
        this.educationLevel = getuser.profile.educationLevel,
        this.roles = getuser.roles[0]
        
       console.log(this.user)
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
  .grup{
    &_label{
    display: flex;
    justify-content: space-between;
    }
    &_coll{
      flex: 1;
    }
  }
  .toogge_button{
    padding: 5px 10px;
    transition: all 0.1s ease-out;
    border-width: 2px;
    border-style: outset;
    border-color: buttonborder;
    border-image: initial;
    text-align: center;
    background-color: buttonface;
    width: 200px;
    &:hover{
      background-color: rgb(156, 156, 154);
      cursor: pointer;
    }
  }
 @media (min-width: 1024px) {
  
 }
</style>