<template>
  <div class="sections fon">
    <div class="wrapper">
      <Loader
        v-if="isLoading"
      />
      <div class="block"
        v-else
      >
        <div class="title">
          <h3>Профиль</h3>  
        </div>
        
            
        <div class="tablis-header-title">
          <div>Ник: <span>{{ getAutchUserProfile.firstName}}</span></div>
          <div>Email: <span>{{ getAutchUserProfile.email }}</span></div>
          <div>Права: <span>{{ getAutchUserProfile.roles[0]  }}</span></div>
        </div>
           
        <div class="button-cont">
          <Button
            title="Выйти из профиля"
            @click="logOut"
          />
        </div>      
                
           
        

      </div>
  </div>
</div>
</template>
 
<script>
  
  import Loader from '../components/ui/LoaderView.vue'
  import { mapGetters, mapActions, mapMutations} from "vuex"
  import Button from '../components/ui/Button.vue'
  export default {
    components: {
      Loader,
      Button
    },
    data() {
      return {
        isLoading: true
      }
    },
    computed: {
      ...mapGetters(["getAuthAccountResult","getAutchUserProfile"]),
      
    },
   
    methods: { 
      ...mapActions(["getAutсhUserProfileDb", "getLogOutUser"]),
      ...mapMutations([]),
      statistikDate({date}){
        let dateToday = date.split('T')
        return  dateToday[0]
      },
      async logOut(){
      await  this.getLogOutUser()
      this.$router.push({ name: 'home'})
    },
    },
      async mounted(){
      
     },
     async created(){
      await this.getAutсhUserProfileDb()
     
      this.isLoading = false
     },
     
 } 
 
</script>
<style lang="scss" scoped>
   .fon{

    background-color: var(--color-fon);
  }
  .sections{
    padding-top: 41px;
    min-height: 100vh;
  }
  .block{
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    background-color: var(--color-white);
    padding: 25px 19px 28px 43px;
  }
  .title{
    font-weight: 700;
    font-size: 30px;
    line-height: 40px;
    color: var(--color-Black_blue);
  }
   
  .tablis{
    
    margin: 10px 10px 10px;
    
    &-header{
      
      &-title{
        font-weight: 700;
        font-size: 20px;
        line-height: 40px;
        color: var(--color-blue);
        span{
          color: var(--color-Black_blue)
        }
      }
    }
  }
  .button-cont{
    display: flex;
    justify-content: end;
  }
 @media (min-width: 1024px) {
  
 }
</style>