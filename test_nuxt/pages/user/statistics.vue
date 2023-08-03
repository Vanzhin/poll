<template>
  <div class="cont"> 
    <UiLoaderView
      v-if="loader.isLoader"
    />
  <div class="fon"
    v-else
  >
    <div class="wrapper">
      <div class="container">
        <div class="title"
        v-html="result.getStatistiks ? 'Результаты прохождения тестов' 
          : `Данных с результатами не найдено. <br> Пройдите тестирование.`"
      >
      </div>
        
          <div v-for="(result, index ) in results" 
            :key="result.score + '_' + index"
          >
            <div class="col-sm-12 col-md-12 col-lg-12"> 
              <div class="tablis-container">
                <div class="tablis-header">
                  <div  class="tablis-header-cont">
                    <div class="">
                      {{ index+1 }}.
                    </div>
                    <div class="tablis-header-title">
                      <div>Категория: </div>
                      <div>{{ result.test.category.title  }}</div>
                    </div>
                  </div>
                  <button class="tablis-header-button"
                    @click="selectReport(result, index)"
                  >
                    <svg 
                      v-if="!result.answerVisible"
                      width="25" height="25" viewBox="0 0 25 25" 
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M8.5 8.70801H10V7.20801V4.70801H15V7.20801V8.70801H16.5H18.3787L12.5 14.5867L6.62132 8.70801H8.5ZM8.5 4.20801V5.70801V7.20801H7H5.41421C4.52331 7.20801 4.07714 8.28515 4.70711 8.91511L11.7929 16.0009C12.1834 16.3914 12.8166 16.3914 13.2071 16.0009L20.2929 8.91511C20.9229 8.28515 20.4767 7.20801 19.5858 7.20801H18H16.5V5.70801V4.20801C16.5 3.65572 16.0523 3.20801 15.5 3.20801H9.5C8.94772 3.20801 8.5 3.65572 8.5 4.20801ZM2.5 19.208V16.208H4V19.208C4 19.4841 4.22386 19.708 4.5 19.708H20.5C20.7761 19.708 21 19.4841 21 19.208V16.208H22.5V19.208C22.5 20.3126 21.6046 21.208 20.5 21.208H4.5C3.39543 21.208 2.5 20.3126 2.5 19.208Z" fill="white"/>
                    </svg>
                    {{ result.answerVisible ? 'Скрыть':'Подробнее' }}
                  </button>
                </div>
                <div class="tablis-header-test">
                  <div class="title-icon">
                    <svg width="24" height="25" viewBox="0 0 24 25" 
                      fill="none" xmlns="http://www.w3.org/2000/svg"
                      v-if="result.pass"
                    >
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M12 23.1659C6.3756 23.1659 1.8 18.5903 1.8 12.9659C1.8 7.34154 6.3756 2.76594 12 2.76594C17.6244 2.76594 22.2 7.34154 22.2 12.9659C22.2 18.5903 17.6244 23.1659 12 23.1659ZM12 0.965942C5.373 0.965942 0 6.33894 0 12.9659C0 19.5935 5.373 24.9659 12 24.9659C18.6276 24.9659 24 19.5935 24 12.9659C24 6.33894 18.6276 0.965942 12 0.965942Z" fill="#56D062"/>
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9818 9.28007C15.8552 9.15827 15.65 9.15827 15.5234 9.28007L11.066 13.5731L9.17003 11.7473C9.04403 11.6255 8.83823 11.6255 8.71162 11.7473L7.64183 12.7781C7.51523 12.8999 7.51523 13.0979 7.64183 13.2197L10.8368 16.2971C10.8998 16.3583 10.9832 16.3889 11.066 16.3889C11.1488 16.3889 11.2316 16.3583 11.2952 16.2971L17.0516 10.7525C17.1128 10.6937 17.1464 10.6145 17.1464 10.5317C17.1464 10.4489 17.1128 10.3691 17.0516 10.3109L15.9818 9.28007Z" fill="#56D062"/>
                    </svg>
                    <svg width="24" height="24" viewBox="0 0 24 24" 
                      fill="none" xmlns="http://www.w3.org/2000/svg"
                      v-else
                    >
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M12 22.2C6.3756 22.2 1.8 17.6244 1.8 12C1.8 6.3756 6.3756 1.8 12 1.8C17.6244 1.8 22.2 6.3756 22.2 12C22.2 17.6244 17.6244 22.2 12 22.2ZM12 0C5.373 0 0 5.373 0 12C0 18.6276 5.373 24 12 24C18.6276 24 24 18.6276 24 12C24 5.373 18.6276 0 12 0Z" fill="#EE3F58"/>
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M9.44676 7.82643C9.01325 7.39119 8.31038 7.39119 7.87686 7.82643C7.44335 8.26168 7.44335 8.96735 7.87686 9.40259L10.4381 11.974L7.82514 14.5974C7.39162 15.0327 7.39162 15.7383 7.82514 16.1736C8.25865 16.6088 8.96152 16.6088 9.39503 16.1736L12.008 13.5502L14.605 16.1576C15.0385 16.5928 15.7414 16.5928 16.1749 16.1576C16.6084 15.7223 16.6084 15.0166 16.1749 14.5814L13.5779 11.974L16.1232 9.41862C16.5567 8.98337 16.5567 8.2777 16.1232 7.84246C15.6896 7.40721 14.9868 7.40721 14.5533 7.84246L12.008 10.3979L9.44676 7.82643Z" fill="#EE3F58"/>
                    </svg>
                  </div>
                  <div>Тест: {{ result.test.title  }}</div>
                </div>
                <div class="tablis">
                  <div class="tablis-cell">
                    <div class="tablis-cell-row1">Билет</div>
                    <div class="tablis-cell-row2">
                      {{ result.ticket ? `№ ${result.ticket.title}`: 
                    result.mode ? result.mode : '' }}</div>
                  </div>
                  <div class="tablis-cell">
                    <div class="tablis-cell-row1">Правильных ответов</div>
                    <div class="tablis-cell-row2">
                      {{ result.correctQuestionCount  }}</div>
                  </div>
                  <div class="tablis-cell">
                    <div class="tablis-cell-row1">Всего вопросов</div>
                    <div class="tablis-cell-row2">
                      {{ result.questionCount }}</div>
                  </div>
                  <div class="tablis-cell">
                    <div class="tablis-cell-row1">Дата прохождения</div>
                    <div class="tablis-cell-row2">
                      {{ statistikDate({date:result.updatedAt}) }}</div>
                  </div>
                </div>
                
                <div 
                  v-if="result.answerVisible"
                >
                  <div v-for="(question, index ) in result.getResultQuestions" 
                    :key="question.id"
                  >
                    <ResultViewStatistick
                      :question="question"
                      :index="index"
                    />
                  </div>
                </div>
              
                  <div class="result-button-block"
                    v-if="result.answerVisible"
                  >
                    <button class="tablis-header-button"
                      @click="selectReport(result, index)"
                    >{{ result.answerVisible ? 'Скрыть':'Подробнее' }}</button>
                    <button class="tablis-header-button"
                      @click="startReportPrint(result, index)"
                      v-if="result.answerVisible"
                    >Протокол</button>
                    <button class="tablis-header-button"
                      v-if="result.test.minTrudTest"
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

<script setup>
  import { useLoaderStore } from '../../stores/Loader'
  import { useResultStore } from '../../stores/ResultStore'
  const loader = useLoaderStore()
  const result = useResultStore()
  const results = ref([])
  const resultIndexVisible = ref(0)
  
  await result.getAuthAccountResultsDb()
  results.value = result.getStatistiks
  function statistikDate({date}){
    let dateToday = date.split('T')
    return  dateToday[0]
  }

  async function selectReport(res, index){
    if (res.answerVisible) {
      res.answerVisible = false
      return
    }
    //this.isLoading = true
    results.value[resultIndexVisible.value].answerVisible = false
    await result.getResultIdAnswersDb({id: res.id})
    resultIndexVisible.value = index
    results.value[resultIndexVisible.value].answerVisible = true
    //this.isLoading = false
  }
  
</script>
<style lang="scss" scoped>
 .cont{
   min-height: 90vh;
 }
 .title{
   margin-top: 0;
   padding-top: 67px;
   padding-bottom: 24px;
   font-weight: 700;
   font-size: 30px;
   line-height: 40px;
   color: #0B1F33;
   @media (max-width: 350px) {
     font-size: 16px;
     line-height: 19px;
     padding-top: 35px;
     padding-bottom: 15px;
   }
   &-icon{
     width: 25px;
     margin-right: 5px;
   }
 }
   
 .resultTrue{
   border: 1px solid #56D062;
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
       @media (max-width: 350px){
        flex-direction: column;
       }
     }
   }
 }
 .tablis{
   margin-top: 20px;
   display: flex;
   justify-content: space-between;
   flex-wrap: wrap;
   @media (max-width: 480px){
     justify-content:center;
   }
   @media (max-width: 350px){
     margin-top: 10px;
   }
   &-container{
     padding-top: 24px;
     padding-bottom: 74px;
     border-bottom: 1px solid var(--color-blue);
     @media (max-width: 350px) {
       padding-bottom: 30px;
     }
   }
   
   &-cell{
     display: flex;
     align-items: center;
     flex-direction:column;
     width: 255px;
     height: 130px;
     text-align: center;
     background-color: #FFFFFF;
     margin: 0;
     margin-top: 10px;
     padding: 0 15px;
     @media (max-width: 350px){
       width: 100%;
       height: 100px;
     }
     &-row1{
       display: flex;
       flex-direction: row;
       align-items: center;
       justify-content: center;
       width: 100%;
       font-weight: 700;
       font-size: 18px;
       line-height: 40px;
       text-align: center;
       color: var(--color-Black_blue);
       border-bottom: 1px solid var(--color-blue);
       height: 58px;
       @media (max-width: 350px) {
         font-size: 16px;
         line-height: 19px;
       }
     }
     &-row2{
       font-weight: 700;
       font-size: 16px;
       line-height: 19px;
       color: var(--color-blue);
       margin-top: 10px;
     }
   }
   &-header{
     display: flex;
     width: 100%;
     font-weight: 700;
     font-size: 20px;
     line-height: 24px;
     color: #0B1F33;
     justify-content: space-between;
     flex-wrap: wrap;
     padding-top: 67px;
     @media (max-width: 350px) {
       font-size: 16px;
       line-height: 19px;
       padding-top: 0px;
     }
     &-cont{
       display: flex;
     }
     &-title{
       margin-left: 12px;
     }
     &-test{
       display: flex;
       font-weight: 700;
       font-size: 20px;
       line-height: 40px;
       color: var(--color-blue);
       margin-top: 30px;
       align-items: center;
       @media (max-width: 350px) {
         font-size: 16px;
         line-height: 19px;
         margin-top: 0px;
       }
     }
     &-button{
       margin-top: 15px;
       display: flex;
       justify-content: center;
       align-items: center;
      
       width: 201px;
       height: 36px;
       background: var(--color-blue);
       border: 1px solid var(--color-blue);
       border-radius: 6px;
       font-weight: 700;
       font-size: 18px;
       line-height: 24px;
       color: #FFFFFF;
       @media (max-width: 350px) {
         margin: 15px auto;
         font-size: 16px;
         line-height: 19px;
       }
       & svg{
         margin-right: 10px;
       }
       &:hover{
         background-color: #FFFFFF;
         color: var(--color-blue);
         cursor: pointer;
         & path {
           fill: var(--color-blue);
         }
       }
     }
   }
 }
@media (min-width: 1024px) {
 
}
</style>