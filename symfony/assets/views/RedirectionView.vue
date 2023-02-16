<template>
  <div class="bagraund">
   <section class="wrapper">
    <div 
      class="login-page"
    >
    <div class="login-page-text">Поздравляем!</div>
      <p>Вы успешно авторизовались. 
        И скоро будете перенаправлены на сайт. 
        Если этого не произойдет, кликните по ссылке. </p>
        <RouterLink :to="pageLink"> Обратно на сайт</RouterLink>
    </div>
  </section>
</div>
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
        checkbox:'false'
      }
    },
    computed:{
      ...mapGetters(["getPageName"]),
      pageLink() {
        return this.getPageName===""? "/": this.getPageName
      }
    },
    methods: {
      ...mapActions(["getAuthRefresh",]),
      
    },
   async mounted(){
      if (this.$route.params.rtoken !== "" ) {
        await this.getAuthRefresh(this.$route.params.rtoken)
        console.log(this.getPageName)
        setTimeout(() => {
          this.$router.push( this.getPageName===""? "/": this.getPageName)
        },15000);
        
      }

    }
  } 
</script>

<style lang="scss" scoped>
.bagraund{
  position: fixed;
  top:0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #585858;
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
</style>