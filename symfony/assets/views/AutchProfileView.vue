<template>
   <Loader
    v-if="isLoading"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <h3>Информация профиля:</h3>  
    </div>
    <div class="container">
      <div class="row">
       
          <div class="col-sm-12 col-md-12 col-lg-12"> 
            <div class="card flex-shrink-1 shadow">
              <div class="tablis-header">
                
                <div class="tablis-header-title">
                  <h5>Ник: <span>{{ getAutchUserProfile.firstName}}</span></h5>
                  <h5>Email: <span>{{ getAutchUserProfile.email }}</span></h5>
                  <h5>Права: <span>{{ getAutchUserProfile.roles[0]  }}</span></h5>
                </div>
              </div>
             
            </div>       
          </div>
        

        

      </div>
  </div>
</div>
</template>
 
<script>
  
  import Loader from '../components/ui/LoaderView.vue'
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      
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
      ...mapActions(["getAutсhUserProfileDb", ]),
      ...mapMutations([]),
      statistikDate({date}){
        let dateToday = date.split('T')
        return  dateToday[0]
      }
    },
      async mounted(){
      
     },
     async created(){
      await this.getAutсhUserProfileDb()
      // console.log(this.getAuthAccountResult)
      this.isLoading = false
     },
     
 } 
 
</script>
<style lang="scss" scoped>
  .block{
    background-color: beige;
  }
  .title{
    margin-left: 10px;
  }
   .result::before{
    content: "X  ";
    font-family: Geneva, Arial;
    color:rgb(235, 25, 25);
    font-weight: 900;
    font-style:  oblique ;
  }
  .resultTrue::before{
    content: "V  ";
    color:rgb(22, 204, 104);
    font-style:  normal ;
  }
  .tablis{
    
    margin: 10px 10px 10px;
    &-row{
      display: flex;
    justify-content: space-between;
    }
    &-cell{
      flex: 1;
      text-align: center;
      border: 2px solid rgb(116 116 183);
      margin: 0;
    }
    &-header{
      display: flex;
      &-title{
        margin-left: 10px;
        p{
          font: bold;
          margin: 0;
        }
      }
    }
  }
 @media (min-width: 1024px) {
  
 }
</style>