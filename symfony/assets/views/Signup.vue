<template>
     <section class="wrapper">
      <div class="login-page">
        <form @submit.prevent="submit">
          <div class="login-page-text">Регистрация</div>
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
            <div>
               <MessageView
                v-if="getMessageLogin"
                :message="getMessageLogin"
              />
               
              
            </div>
             
            <div class="text-login">
                  <RouterLink :to="{ name: 'logout'}" class="routerLink"> 
                     <p> Уже есть аккаунт</p>
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
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      MessageView
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
            setTimeout(() => this.SET_MESSAGE_REQUEST(null), 5000);
            
            return
          } else {
            setTimeout(() => this.SET_MESSAGE_REQUEST(null), 7000);
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
  
.login-page {
   max-width: 380px;
   padding: 24px;
   background: #cad5d5;
   box-shadow: 0px 4px 15px rgba(34, 42, 70, 0.08);
   border-radius: var(--radius);
   margin: 100px auto;
   &-text{
      font-weight: 600;
      font-size: 18px;
      line-height: 26px;
      text-align: center;
   }
   
 }
 @media (min-width: 1024px) {
  
 }

 @media (min-width: 1024px) {
  
 }
</style>