<template>
     <section class="wrapper">
      <div class="login-page">
        <CloseView/>
        <form @submit.prevent="submit">
          <div class="login-page-title">Регистрация</div>
          <div class="form">
            <div class="text-field">
              <label class="text-field__label" >Ник</label>
              <input class="text-field__input" 
                id="nik" 
                type="text" 
                placeholder="Придумайте ник"
                v-model="firstName"
                required
              />
            </div>
            <div class="text-field">
              <label class="text-field__label" >Email</label>
              <input class="text-field__input" 
                id="email" 
                type="email" 
                placeholder="Введите email"
                v-model="email"
                required
              />
            </div>
            <div class="text-field">
              <label class="text-field__label" >Пароль</label>
              <input class="text-field__input" 
                id="password" 
                type="password" 
                placeholder="Введите пароль"
                v-model="password"
                required
              />
            </div>
            <div class="text-field">
              <label class="text-field__label" >Повторить пароль</label>
              <input class="text-field__input" 
                id="password_repeat" 
                type="password" 
                placeholder="Повторите пароль"
                v-model="confirmPassword"
                required
              />
            </div>
            
            <div class="text-login">
              <RouterLink :to="{ name: 'logoutlink'}" class="routerLink"> 
                <p> Войти по ссылке</p>
              </RouterLink>
            </div>
            <div class="text-login">
              <RouterLink :to="{ name: 'logout'}" class="routerLink"> 
                 Уже есть аккаунт
              </RouterLink>
            </div>
            <input class="btn" type="submit" value="Зерегистрироваться"/> 
          </div>
        </form>
      </div>
    </section>
</template>
 
<script>
  import MessageView from "../components/ui/MessageView.vue"
  import CloseView from "../components/ui/CloseView.vue"
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      MessageView, CloseView
    },
    data() {
      return {
        count: 0,
        confirmPassword:'',
        password:'',
        email:'',
        firstName:'',
      }
    },
      computed:{
        ...mapGetters(["getMessageLogin", "getPageName"])
      },
      methods: {
        ...mapActions([
          "setIsAutchUser", "setLogInUser", 
          "setRegistrationUser", 
        ]),
        ...mapMutations(["SET_MESSAGE_REQUEST"]),
        async submit(){
          const user = {
            confirmPassword: this.confirmPassword,
            email: this.email,
            firstName: this.firstName,
            password: this.password, 
          }
          console.log('Регистрация')
          const res = await this.setRegistrationUser(user)
          console.log('Регистрация res -', res)
          if (res) {
            return
          } else {
            this.clearForm()
          }
          await  this.setLogInUser({username:user.email,password: user.password})
          console.log(this.getPageName)
          this.$router.push({ path: this.getPageName})
        },
        clearForm() {
          this.confirmPassword = '',
          this.password='',
          this.email='',
          this.firstName=''
        }
      }
      // mounted(){
      // }
    
  } 
 
</script>
<style lang="scss" scoped>
  

 @media (min-width: 1024px) {
  
 }

 @media (min-width: 1024px) {
  
 }
</style>