<template>
  <section class="wrapper">
    
    <div 
      class="login-page"
      v-if="emailSend"
      
    >
      <h1>{{resMessage}} </h1>
      
    </div>
      
    <div 
      class="login-page"
      v-else
      >
        <CloseView/>
        <form @submit.prevent="onSubmit">
          <div class="login-page-title">Вход на сайт</div>
          <div class="login-page-text">
            Введите действующий email<br>
            Вам на почту придет ссылка для авторизации
          </div>
          
          <div class="form">
            <div class="text-field">
              <label class="text-field__label" >E-mail</label>
              <input class="text-field__input"
                type="email" 
                placeholder="Введите email"
                name="email"
                v-model="email"
                required
              />
            </div>
            <div class="text-login">
                <RouterLink :to="{ name: 'logout'}" class="routerLink"> 
                    <p> Войти с паролем</p>
                </RouterLink>
              </div>
            <div class="text-login">
                <RouterLink :to="{ name: 'signup'}" class="routerLink"> 
                    Пройти регистрацию
                </RouterLink>
              </div>
            <input class="btn" type="submit" value="получить ссылку"/>
          </div>
          <div
            v-if="resMessage!==''"
          >
            {{ resMessage }}
          </div>
        </form>
    </div>
     
  </section>
</template>
 
<script>
  import { mapGetters, mapActions} from "vuex"
  import CloseView from "../components/ui/CloseView.vue"
  import { RouterLink } from 'vue-router'
  export default {
    components: {
      CloseView
    },
    data() {
      return {
        count: 0,
        email:'',
        emailSend: false,
        resMessage: '',
        link: ''
      }
    },
    computed:{
      ...mapGetters(["getPageName", "getLogoutLinkDate"])
    },
    methods: {
      ...mapActions(["setIsAutchUser","getLoginByLinkUser"]),
      async onSubmit(e){
        await this.getLoginByLinkUser(this.email)
        this.emailSend = this.getLogoutLinkDate.send,
        this.resMessage = this.getLogoutLinkDate.message,
        this.link = this.getLogoutLinkDate.url
       
      }
    }
     
  } 
</script>

<style lang="scss" scoped>

@media (min-width: 1024px) {
  
}
</style>