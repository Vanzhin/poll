<template>
  <section class="wrapper">
     
    <div class="login-page">
     
      <CloseView/>
      <form @submit.prevent="onSubmit">
          <div class="login-page-text">Вход на сайт</div>
          <div class="form">
              <div class="text-field">
                <label class="text-field__label" >Email</label>
                <input class="text-field__input"
                  type="email" 
                  placeholder="Введите email"
                  name="email"
                  v-model="email"
                  required
                />
              </div>
              <div class="text-field">
                <label class="text-field__label" >Пароль</label>
                <input class="text-field__input"
                  type="password" 
                  name="password"
                  placeholder="Введите пароль"
                  v-model="password"
                  required
                />
              </div>
         
             
              <div class="text-login">
                <RouterLink :to="{ name: 'logoutlink'}" class="routerLink"> 
                  <p> Войти по ссылке</p>
                </RouterLink>
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
  
  import CloseView from "../components/ui/CloseView.vue"
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
       CloseView
    },
    data() {
      return {
        count: 0,
        email:'',
        password:'',
        checkbox:'false'
      }
    },
    computed:{
      ...mapGetters(["getPageName", "getMessageLogin"])
    },
    methods: {
      ...mapActions(["setIsAutchUser","setLogInUser"]),
      ...mapMutations(["SET_MESSAGE_REQUEST"]),
      async onSubmit(e){
        console.log('авторизация')
        const user = {
          username: this.email, 
          password: this.password, 
        }
        console.log(user)
        const res = await this.setLogInUser(user)
        if (!res) {
          setTimeout(() => 
            {
              this.$router.push({ path: this.getPageName})
            }, 5000);
        }
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
p{
  margin: 0;
}
@media (min-width: 1024px) {
  
}
</style>