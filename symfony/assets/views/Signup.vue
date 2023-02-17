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
               <div class="cont-message"
                v-if="getMessageLogin"
              >
                <div class="cont-message-view">
                  <p>{{ getMessageLogin.mes }}</p>
                </div> 
              </div>
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
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      
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
        ...mapGetters(["getMessageLogin"])
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
            setTimeout(() => this.SET_MESSAGE_REQUEST(null), 5000);
            this.clearForm()
          }
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
  .cont-message{
    position: relative;
    width: 100%;
    
    &-view{
      position: absolute;
      min-height: 50px;
      min-width: 200px;
      bottom: -26px;
      width: 100%;
      z-index: 10;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #9ac7c7;
      border-radius: 10px;
      animation: move 5s 1 linear;
      text-align: center;
      transform: scaleY(0);
      padding: 15px;
      p{
          color: rgb(217 50 80);
          margin: 0;
      }
    }
  }

  @keyframes move {
   0% {
      transform: scaleY(0.5);
      opacity:  0.5;
   }
   5% {
      transform: scaleY(1);
      opacity:  1;
     }
   80% {
      transform: scaleY(1);
      opacity:  1;
    
     }  
   100% {
      transform: scaleY(0);
      opacity:  0;
   }
}


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