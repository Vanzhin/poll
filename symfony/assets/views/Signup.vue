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
                v-model="nik"
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
                v-model="passwordRepeat"
                required
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
  
  export default {
    components: {
      
    },
    data() {
      return {
        count: 0,
        passwordRepeat:'',
        password:'',
        email:'',
        nik:'',
      }
    },
      computed:{
        
      },
      methods: {
        submit(){
          console.log('Регистрация')
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