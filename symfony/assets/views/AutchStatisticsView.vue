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
        <div v-for="(result, index ) in results" 
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
                <ResultAutchView 
                  v-if="result.answerVisible"
                />
                <div class="result-button-block">
                  <button class="result-button"
                    @click="selectReport(result, index)"
                  >{{ result.answerVisible ? 'Скрыть':'Подробнее' }}</button>
                  <button class="result-button"
                    @click="getReport(result.id)"
                  >Скачать XML-файл</button>
                </div>
              </div>
            </div>       
          </div>
        </div>

        

      </div>
  </div>
</div>
</template>
 
<script>
  import ResultAutchView from './ResultAutchView.vue' 
  import Loader from '../components/ui/LoaderView.vue'
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      ResultAutchView
    },
    data() {
      return {
        isLoading: true,
        resultIndexVisible:0,
        results:[]
      }
    },
    computed: {
      ...mapGetters(["getAuthAccountResult","getAutchUserToken"]),
    },
   
    methods: { 
      ...mapActions([
        "getAuthAccountDb", 
        "getAuthAccountResultsDb",
        "getResultsXmlDb",
        "getResultIdAnswersDb"
      ]),
      ...mapMutations([]),
      statistikDate({date}){
        let dateToday = date.split('T')
        return  dateToday[0]
      },
      async getReport(id){
       // this.isLoading = true
        await this.getResultsXmlDb({id})
        //this.isLoading = false
      },
      async selectReport(result, index){
        if (result.answerVisible) {
          result.answerVisible = false
          return
        }
        //this.isLoading = true
       
        this.results[this.resultIndexVisible].answerVisible = false
        await this.getResultIdAnswersDb({id: result.id})
        this.resultIndexVisible = index
        this.results[this.resultIndexVisible].answerVisible = true
        //this.isLoading = false
      }

    },
      async mounted(){
      
     },
     async created(){
      await this.getAuthAccountResultsDb()
      this.results = this.getAuthAccountResult
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
  .result{
    &-button{
      padding: 5px;
      &:hover{
        background-color: rgb(179 179 179);
      }
      &-block{
        display: flex;
        justify-content: space-around;
        margin-top: 10px;
      }
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