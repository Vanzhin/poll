<template>
   <section class="wrapper">
      <div class="login-page">
         <form @submit.prevent="submit">
            <div class="login-page-text">Вход на сайт</div>
            <div class="form">
               <div class="text-field">
                  <label class="text-field__label" >Email</label>
                  <input class="text-field__input"
                    type="email" 
                    placeholder="Введите email"
                    v-model="email"
                    required
                  />
               </div>
               <div class="text-field">
                  <label class="text-field__label" >Пароль</label>
                  <input class="text-field__input"
                    type="password" 
                    placeholder="Введите пароль"
                    v-model="password"
                    required
                  />
               </div>
               <div>
                <label class="checkbox__container" >
                  <input type="checkbox"  class="checkbox__highload"
                  v-model = "remember"
                    
                  />
                  <span class="checkbox__highload2"></span>
                  запомнить меня
                </label>
               </div>
               <div class="text-login">
                  <RouterLink :to="{ name: 'signup'}" class="routerLink"> 
                     <p> Пройти регистрацию</p>
                  </RouterLink>
               </div>
                  <input class="btn" type="submit" value="Войти"/>
                  
            </div>
         </form>
      </div>
    </section>
</template>
 
<script>
 import { RouterLink } from 'vue-router'
 import { mapGetters, mapActions} from "vuex"
 export default {
   components: {
    
   },
   data() {
     return {
       count: 0,
       email:'',
       password:'',
       checkbox:'true'
     }
   },
    computed:{
      ...mapGetters(["getPageName"])
     },
     methods: {
      ...mapActions(["setIsAutchUser",]),
      async submit(){
          console.log('авторизация')
          await this.setIsAutchUser()
          console.log('this.getPageName', this.getPageName)
          this.$router.push({ name: this.getPageName})
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
</style>