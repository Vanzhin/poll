<template>
  <section class="wrapper">
     
    <div class="login-page">
     
      <CloseView/>
      <form @submit.prevent="onSubmit">
        <div class="login-page-title">Авторизация</div>
       
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
                   Пройти регистрацию
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
            }, 3000);
        }
      }
    }
     // mounted(){
     // }
  } 
</script>

<style lang="scss" scoped>


@media (min-width: 1024px) {
  
}
</style>