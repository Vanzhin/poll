<template>
   <Loader
    v-if="isLoading"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <h1>Результаты прохождения тестов:</h1>  
    </div>
    <div class="container">
      <div class="row">
        <div v-for="(result, index ) in getAuthAccountResult" 
          :key="result.score + '_' + index"
        >
          <div class="col-sm-12 col-md-12 col-lg-12"> 
            <div class="card flex-shrink-1 shadow">
              <div class="tablis-header">
                <div class="">
                  <i class="result"
                    :class="{resultTrue: result.score > 0 }"
                  > </i><b>{{ index+1 }})</b> 
                </div>
                <div class="tablis-header-title">
                  <h5>Категория: <span>{{ result.test.category.title  }}</span></h5>
                  <h5>Тест: <span>{{ result.test.title  }}</span></h5>
                </div>
              </div>
              <div class="tablis">
                <div class="tablis-row">
                  <h5 class="tablis-cell">Билет</h5>
                  <h5 class="tablis-cell">Правильных ответов</h5>
                  <h5 class="tablis-cell">Всего вопросов</h5>
                  <h5 class="tablis-cell">Дата прохождения</h5>
                </div>
                <div class="tablis-row">
                  <p class="tablis-cell">{{ result.ticket ? `№ ${result.ticket.title}`: 
                    result.mode ? result.mode : '' }}</p>
                  <p class="tablis-cell">{{ result.correctQuestionCount }}</p>
                  <p class="tablis-cell">{{ result.questionCount }}</p>
                  <p class="tablis-cell">{{ statistikDate({date:result.updatedAt}) }}</p>
                </div>
                <button
                  @click="getReport(result.id)"
                >Получить отчет</button>
              </div>

              

              
              
             
            </div>       
          </div>
        </div>

        

      </div>
  </div>
</div>
</template>
 
<script>
  import ResultTestQuestion from '../components/ResultQuestion/ResultTestQuestion.vue'
  import Loader from '../components/ui/Loader.vue'
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      ResultTestQuestion
    },
    data() {
      return {
        isLoading: true
      }
    },
    computed: {
      ...mapGetters(["getAuthAccountResult","getAutchUserToken"]),
      
    },
   
    methods: { 
      ...mapActions(["getAuthAccountDb", "getAuthAccountResultsDb","getResultsXmlDb"]),
      ...mapMutations([]),
      statistikDate({date}){
        let dateToday = date.split('T')
        return  dateToday[0]
      },
      async getReport(id){
        this.isLoading = true
        await this.getResultsXmlDb({id})
        this.isLoading = false
      }

    },
      async mounted(){
      
     },
     async created(){
      await this.getAuthAccountResultsDb()
      console.log(this.getAuthAccountResult)
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