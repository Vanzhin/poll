<template>
  <!-- <HeadesPage
    :title="crumbs"
    :subTitle="testName"
    v-if="!getIsLoaderStatus"
  /> -->
  
  <div class="fon "
   
  >
    <UiLoaderView
      v-if="loader.isLoader"
    />
    <div class="container"
      v-else
    >
    <TheHeaderVsPage
        title=""
        :subTitle="tickets.testTitle"
      />
      <div class="wrapper">
        <div class="tickets-block">
          <div class="tickets-block-coll-tickets">
            <div class="tickets-block-coll-tickets-title">
              Выберите тестирование
              <div
                class="tickets-block-coll-tickets-title-icon"
                @click.stop="isVisible = !isVisible"
              >
                <svg width="24" height="24" 
                  viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <circle opacity="0.7" cx="12" cy="12" r="10" fill="#F8B200"/>
                  <path d="M11.2977 13.5625H12.6977L12.9217 10.602V7.19995H11.0737V10.602L11.2977 13.5625ZM11.0737 17H12.9217V15.068H11.0737V17Z" fill="white"/>
                </svg>
              </div>
            </div>

            <div class="tickets-block-coll-tickets-block">
              <div
                @click="clickToLink('rndmax')"
                class="tickets-block-coll-tickets-item"
              >
                Тестирование по всем вопросам
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
                  <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
                </svg>
              </div>
            
              <div  
                @click="clickToLink('rndb')"
                class="tickets-block-coll-tickets-item"
                v-if="tickets.getTicketsIs"
              >
                Случайный билет
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
                  <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
                </svg>
              </div>
              
              <div
                @click="clickToLink('rnd')"
                class="tickets-block-coll-tickets-item"
              >
                Случайный набор
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
                  <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
                </svg>
              </div>
              
              <div
                @click="clickToLink('rnd20')"
                class="tickets-block-coll-tickets-item">
                Случайный набор из 20 вопросов
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
                  <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
                </svg>
              </div>
              
              <div  
                class="tickets-block-coll-tickets-item"
                @click="clickToLink('rnd20t')"
              >
                Случайный набор из 20 вопросов (таймер)
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
                  <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
                </svg>
              </div>
            
            </div>
            <div class="tickets-block-coll-tickets-block"
              v-if="tickets"
            >
              <div 
                class="tickets-block-coll-tickets-itemticket"
                v-for="(ticket) in tickets.tickets"
                :key="ticket.id"
                @click="clickToLinkTicket({ticket})"
              >
                Билет № {{ ticket.title }}
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
                  <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" 
                    fill="#FFFFFF"/>
                </svg>
              </div>
            </div>
          </div>
          <div class="tickets-block-coll-info"
            :class="{'tickets-block-coll-info-visible': isVisible}"
            @click.stop="isVisible = !isVisible"
          >
            <div class="tickets-block-coll-info-item">
              <div>
                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <circle opacity="0.7" cx="12" cy="12.473" r="10" fill="#F8B200"/>
                  <path d="M11.2982 14.0355H12.6982L12.9222 11.075V7.67297H11.0742V11.075L11.2982 14.0355ZM11.0742 17.473H12.9222V15.541H11.0742V17.473Z" fill="white"/>
                </svg>
              </div>
              <div class="tickets-block-coll-info-item-cont">
                <div class="tickets-block-coll-info-item-title">
                  Случайный билет
                </div>
                <div class="tickets-block-coll-info-item-text">
                  При выборе случайного билета система сама сделает выбор. Случайный набор сгенерирует билет с произвольным составом вопросов. 
                </div>
              </div>
            </div>
            <div class="tickets-block-coll-info-item">
              <div>
                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M20.5 12.473C20.5 17.1674 16.6944 20.973 12 20.973C7.30558 20.973 3.5 17.1674 3.5 12.473C3.5 7.7786 7.30558 3.97302 12 3.97302C16.6944 3.97302 20.5 7.7786 20.5 12.473ZM22 12.473C22 17.9959 17.5228 22.473 12 22.473C6.47715 22.473 2 17.9959 2 12.473C2 6.95017 6.47715 2.47302 12 2.47302C17.5228 2.47302 22 6.95017 22 12.473ZM11.25 7.5036V12.1374C11.25 12.8726 11.5444 13.5772 12.0675 14.0938L13.9729 15.976L15.0271 14.9089L13.1216 13.0267C12.8838 12.7918 12.75 12.4716 12.75 12.1374V7.5036H11.25Z" fill="#269EB7"/>
                </svg>

              </div>
              <div class="tickets-block-coll-info-item-cont">
                <div class="tickets-block-coll-info-item-title">
                  Таймер
                </div>
                <div class="tickets-block-coll-info-item-text">
                  Выбор набора с пометкой "Таймер" ограничит время на прохождение теста.
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tickets-block-info">
          Дополнительная информация
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
          </svg>

        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
  import { storeToRefs } from 'pinia'
  // import { usePaginationStore } from '../../stores/PaginationStore'
  import { useTestsStore } from '../../../stores/TestsStore'
  import { useTicketsStore } from '../../../stores/TicketsStore'
  import { useLoaderStore } from '../../../stores/Loader'
  const tests = useTestsStore()
  const {getTests} = storeToRefs(tests)
  const loader = useLoaderStore()
  const tickets = useTicketsStore()
  const route = useRoute()
  const parentId = ref(+route.params.id)
  const iterNum = ref(+route.params.num)
  const page = ref(route.params.page ? +route.params.page: 1)
  const isVisible = ref(false)
  
  console.log(tests)
  console.log(route.params)
  console.log(parentId)
  
  useSeoMeta({
    title: 'Амулет Тест | Категория | Тест',
    ogTitle: 'Амулет Тест | ',
    description: 'Сервис онлайн тестирования по вопросам охраны труда, промышленной безопасности (тесты Ростехнадзора), электробезопасности, тепловые установки. Онлайн подготовка и проверка знаний.',
    ogDescription: 'This is my amazing site, let me tell you all about it.',
    ogImage: 'https://example.com/image.png',
    twitterCard: 'summary_large_image',
  })
  
  
  
  
  
    
  tickets.getApiTicketsTestIdNoAuthDb({
    page: page.value > 1 ? page.value: '',
    parentId: parentId.value,
  })

  function clickToLink(link){
    navigateTo(`/test/${parentId.value}/${link}`)
  }
  function clickToLinkTicket({ticket}){
    navigateTo(`/test/${parentId.value}/ticket/${ticket.id}`)
  }
</script>
<style lang="scss" scoped>
  .fon{
    background-color: var(--color-fon);
    min-height: 90vh;
    @media (max-width: 350px) {
      min-height: 66vh;
    }
  }
  .tickets{
    
    &-block{
      width: 100%;
      font-family: 'Lato';
      font-style: normal;
      display: flex;
      justify-content: space-between;
      padding-top: 28px;
      padding-bottom: 94px;
      flex-wrap: wrap;
      @media (max-width: 480px) {
        padding-top: 8px;
        position: relative;
        padding-bottom: 0px;
      }
      &-coll{
        &-info{
          padding: 24px;
          width: 285px;
          min-height: 376px;
          background-color: #ffffff;
          filter: drop-shadow(0px 1px 4px rgba(92, 101, 119, 0.26)) drop-shadow(0px 24px 48px rgba(109, 120, 147, 0.1));
          @media (max-width: 350px) {
            width: 296px;
            position: absolute;
            top: 50px;
            display: none;
          }
          @media (max-width: 480px) {
            width: 100%;
            min-height:285px;
          }
          &-visible{
            display: block;
          }
          &-item{
            display: flex;
            margin-bottom: 24px;
            
            &-title{
              font-weight: 700;
              font-size: 16px;
              line-height: 24px;
              
            }
            &-text{
              font-weight: 400;
              font-size: 16px;
              line-height: 24px;
              color: #0B1F33;
              margin-top: 8px;
            }
            &-cont{
              padding-left: 16px;
            }

          }
        }
        &-tickets{
          display: flex;
          flex-wrap: wrap;
         
          flex-direction: column;
          @media (max-width: 480px) {
            justify-content: center;
            
          }
          &-block{
            
            display: flex;
            flex-wrap: wrap;
            justify-content:space-between;
            max-width: 782px;

            &:nth-child(2){
                @media (max-width: 480px) {
                  justify-content: center;
                }
              }
            &:nth-child(3){
              @media (max-width: 480px) {
                max-width: 380px;
                
              }
              

            }
          }
          &-item{
            text-decoration: none;
            width: 48%;
            height: 52px;
            padding: 0px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #FFFFFF;
            border: 1px solid var(--color-blue);
            box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
            border-radius: 6px;
            color: var(--color-blue);
            margin-bottom: 15px;
            @media (max-width: 768px) {
              width: 90%;
            }
            
            
            &:nth-child(odd) {
              margin-right: 24px;
              @media (max-width: 480px) {
                margin-right: 0px;
              }
            }
            @media (max-width: 480px) {
              margin-right: 0px;
              width: 100%;
            }
            &:hover{
              cursor: pointer;
              background-color: var(--color-blue);
              color: #FFFFFF;
              path {
                fill:#FFFFFF;
              }
            }
          }
          &-itemticket{
            text-decoration: none;
            width: 173px;
            height: 52px;
            padding: 14px 10px;
            display: flex;
            justify-content: space-between;
            background: var(--color-blue);
            border: 1px solid var(--color-blue);
            box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
            border-radius: 6px;
            color: #FFFFFF;
            margin-bottom: 15px;
            @media (max-width: 350px) {
              width: 136px;
            }
           
            &:hover{
              cursor: pointer;
              background-color: #FFFFFF;
              color: var(--color-blue);
              path {
                fill:var(--color-blue);
              }
            }
          }

          &-title{
            font-weight: 700;
            font-size: 20px;
            line-height: 40px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            @media (max-width: 350px) {
              font-size: 16px;
              margin-bottom: 20px;
            }
            &-icon{
                display: none;
                @media (max-width: 350px) {
                  display: inline-flex;
                }
              }
          }
        }
      }
      &-info{
      display: flex;
      align-items: center;
      font-weight: 700;
      font-size: 16px;
      line-height: 24px;
      color: var(--color-blue);
      margin-top: 63px;
      padding-bottom: 63px;
      @media (max-width: 350px) {
        margin-top: 30px;
        padding-bottom: 30px;
      }

      &:hover{
        cursor: pointer;
      }
    }
    }
    
  }
@media (min-width: 1024px) {
 
}
</style>