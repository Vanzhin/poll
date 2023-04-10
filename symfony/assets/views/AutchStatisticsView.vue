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
                <div class="container"
                  v-if="result.answerVisible"
                >
                  <div class="row">
                    <div v-for="(question, index ) in getResultQuestions" 
                      :key="question.id"
                      v-if="result.answerVisible"
                    >
                     <StatistickResultQuestion
                      :question="question"
                      :index="index"
                     />
                    </div>
                  </div>
                </div>
             
                <div class="result-button-block">
                  <button class="result-button"
                    @click="selectReport(result, index)"
                  >{{ result.answerVisible ? 'Скрыть':'Подробнее' }}</button>
                  <button class="result-button"
                    @click="formProtocol(result, index)"
                    v-if="result.answerVisible"
                  >Протокол</button>
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
  import StatistickResultQuestion from '../components/StatistickResultQuestion.vue'
  import Loader from '../components/ui/LoaderView.vue'
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      StatistickResultQuestion
    },
    data() {
      return {
        isLoading: true,
        resultIndexVisible:0,
        results:[],
        
        count:0
      }
    },
    computed: {
      ...mapGetters(["getAuthAccountResult","getAutchUserToken","getResultQuestions"]),
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
      },
      
      questionId(question){
        return question.question ? question.question.id : question.id
      },
      formProtocol(result, index){
        let block = `<div style="text-align: center">
            <b>Протокол тестирования</b><br>
            ${this.statistikDate({date:result.updatedAt})}
          </div>
          Тестируемый: <b>Инкогнито</b>
          <br>
          <h4>Тест: ${result.test.title}</h4>
          <h4>Билет №: ${result.ticket ? `№ ${result.ticket.title}`: 
                    result.mode ? result.mode : '' }</h4>
          <table style=" width: 100%;"  border= "1" cellpadding="3" cellspacing="0">
            <tr>
            <td "
            >Вопросы</td>
           
            <td ">
              Ответы
            </td></tr>
          `
          
          const question = this.getResultQuestions
          console.log(question)
          question.forEach(element => {
            block += `<tr>
            <td 
            >${element.title}</td>
           
            <td >`
              if (element.type === "input_one") {
                if (element.result.user_answer[0] !== "") {
                block +=`(${element.result.user_answer[0] === element.result.true_answer[0]? '+':'-' }) ${element.result.user_answer[0]}`
                }
              } else if (element.result.user_answer.length > 0) {
               
                element.result.user_answer.forEach((uAnswer, index) => {
                  block +=`(${uAnswer ===element.result.true_answer[index]? '+':'-' }) ${element.variant[uAnswer].title} <br>`              })
              }
              block +=`</td></tr>`

          
          
          console.log(element)})
          block +=`</table>`

          var w = window.open('', '', 'scrollbars=1');
    w.document.write(`<!DOCTYPE html>\n\
        <title>Протокол</title>\n\
        
        ${block}

  `);
          // let link = document.createElement("a");
          // link.setAttribute("href", block);
          // link.setAttribute('target',"_blank");
          // link.click();
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