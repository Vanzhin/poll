<template>
   <section class="wrapper">
      <div class="login-page">
         <form @submit.prevent="onSubmit">
            <div class="login-page-text">Вход на сайт</div>
            <p>Введите действующий email </p>
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
               <p>Вам на почту придет ссылка для авторизации </p>
               <input class="btn" type="submit" value="получить ссылку"/>
                  
            </div>
         </form>
      </div>
    </section>
</template>
 
<script>
  import { mapGetters, mapActions} from "vuex"
  export default {
    components: {
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
      ...mapGetters(["getPageName"])
    },
    methods: {
      ...mapActions(["setIsAutchUser","getLoginByLinkUser"]),
      async onSubmit(e){
        console.log('авторизация')
      
        console.log(this.email)
        await  this.getLoginByLinkUser(this.email)
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