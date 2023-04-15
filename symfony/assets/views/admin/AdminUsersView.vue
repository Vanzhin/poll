<template>
   <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <h2>Пользователи</h2>
    </div>
   <div class="container">
      <div class="row">
        <div class="tablis">
                <div class="tablis-row">
                  <h5 class="tablis-cell">Ник</h5>
                  <h5 class="tablis-cell">Email</h5>
                  <h5 class="tablis-cell">Права</h5>
                  <h5 class="tablis-cell">Дата регистрации</h5>
                </div>
                <div class="tablis-row">
                  <p class="tablis-cell">{{ррр }}</p>
                  <p class="tablis-cell">{{ оо }}</p>
                  <p class="tablis-cell">{{ оо}}</p>
                  <p class="tablis-cell">{{ hh}}</p>
                </div>
               
               
              </div>
      </div>
    </div>
  </div>

  
</template>
 
<script>
  import MessageView from "../../components/ui/MessageView.vue"
  import Loader from '../../components/ui/Loader.vue'
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      MessageView
    },
    data() {
      return {
        isLoader: true,
      }
    },
    computed:{ 
      ...mapGetters(["getAutchUserToken", "getMessage"]),
    },
   
    methods: { 
      ...mapActions(["saveQuestionDb", "setMessage"]),
      ...mapMutations([]),
    },
    async mounted(){
      setTimeout(()=>this.isLoader = false, 1000 )
      
    }
   
 } 
 
</script>
<style lang="scss" scoped>
  .button{
    padding: 5px 10px;
    transition: all 0.1s ease-out;
    &:hover{
      background-color: rgb(156, 156, 154);
    }
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