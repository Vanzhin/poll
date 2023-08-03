<template>
  <div>
    <div v-for="(question, index ) in result.results" 
      :key="question.id"
    >
      <ResultTestQuestionNoAutchQuestion
        :question="question"
        :index="index"
      /> 
    </div>
    <div class="info">
      <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="16.6673" cy="16.8298" r="13.3333" fill="#269EB7"/>
        <path d="M15.3789 12.8938H17.9549V10.4298H15.3789V12.8938ZM15.4909 14.8298V23.4965H17.8429V14.8298H15.4909Z" fill="white"/>
      </svg>
      <div class="info-text">Для просмотра более подробной информации </div>
      <div class="routerLink" @click="clickNavigate()"> 
        <div class="button">
          Авторизуйтесь  
        </div> 
      </div>
    </div>  
  </div>     
</template>
<script setup>
  const route = useRoute()
  import { useResultStore  } from '../../stores/ResultStore'
  const result = useResultStore()
  import { useUserStore  } from '../../stores/UserStore'
  const user = useUserStore()
  async function clickNavigate(link){
    console.log(route.path)
    user.savePage(route.path)
    navigateTo('/user/autch/link')
  }
  
</script> 


<style lang="scss" scoped>
  .button{
    width: 190px;
    height: 36px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid var(--color-blue);
    border-radius: 6px;
    color:var( --color-blue) ;
    @media (max-width: 480px) {
      margin: 20px auto 0 auto;
    }
    &:hover{
      background-color: var(--color-blue);
      color:var( --color-white) ;
      cursor: pointer;
    }
  }
  .info{
    display: flex;
    padding-top: 72px;
    padding-bottom: 90px;
    align-items: center;
    flex-wrap: wrap;
    @media (max-width: 480px) {
      display: block;
    }
    &-text{
      margin-left: 13px;
      font-weight: 700;
      font-size: 20px;
      line-height: 40px;
      margin-right: 30px;
      @media (max-width: 480px) {
        font-size: 16px;
        line-height: 19px;
      }
    }
  }
@media (min-width: 1024px) {
 
}
</style>