<template>
  <Head>
    <link rel="canonical" href=""/>
  </Head>

  <div class="sections fon">
    <UiLoaderView
          v-if="loader.isLoader"
          />
    <div class="wrapper"
    
    v-else>
      <div class="" >
        <div class="b-container">
          <h1 class="sections__title">
            Сервис <span class="sections__title-select">онлайн-тестирования</span> по промышленной безопасности 
            приветствует вас!
          </h1>
          <h2 class="sections__title-h2">
            Выберите область проверки знаний
          </h2>
        </div>
        
        
        <div class="b-container" 
        
        >
          <div class="sections__block row" v-if="categorys.getCategogys !==''">
            <div class="col-sm-12 col-md-6 col-lg-4 col-xs-2 item"
             
              v-for="section in  getCategogys" 
              :key="section.id"
              :style="`background-image: url(${section.image ? section.image 
                :'http://test2-open/img/item_fon.png'});`"
              @click="categoryUpdate({section})"
            > <!-- category.getCategogys -->
              <div class="item-info">
                <div class="item-info-title">{{ section.title }}</div>
                <div class="item-info-discrabe">{{ section.description }}</div>
              </div>
            </div>
          </div>
         
         
          <ThePagination />
        </div> 
        
      </div>
    </div>
  </div>
  <div class="fon">
    <div class="b-container">
      <div class="wrapper">
        <div class="service">
          <div class="service-left-block">
            <div class="service-title">
              О Сервисе
            </div>
            <div class="service-text">
              Сервис представляет собой - группу тестов, <br>
              которая предназначается для подготовки к аттестации:
            </div>
            <div class="service-text-list">
              <ul> 
                <li>по промышленной безопасности по тестам Ростехнадзора;</li>
                <li>на группу по электробезопасности;</li>
                <li>по охране труда;</li>
                <li>водителей, перевозящих опасные грузы (ДОПОГ);</li>
                <li>сварщиков (НАКС);</li>
                <li>на знания ПДД.</li>
              </ul>
            </div>
          </div>
          <div class="service-right-block">
            В сервисе предоставляются тесты Ростехнадзора по промбезопасности 
            и по электробезопасности. Сервис поможет подготовиться к аттестации 
            водителям, занимающимся перевозкой опасных грузов (тесты ДОПОГ), 
            а также сварщикам (тесты НАКС). Кроме того, представлены тесты 
            по пожарной безопасности, экологической безопасности и общим 
            вопросам охраны труда.
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="ticket-fon">
    <div class="b-container">
      <div class="wrapper">
        <div class="ticket">
          <div class="ticket-icon">
            <svg width="56" height="65" viewBox="0 0 56 65" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M18.4667 18.7291H9.30547C8.04055 18.7291 7.01514 19.7545 7.01514 21.0194V53.0841C7.01514 54.349 8.04055 55.3744 9.30547 55.3744H34.4991C35.7641 55.3744 36.7895 54.349 36.7895 53.0841V46.2132H45.9507C47.2156 46.2132 48.2411 45.1878 48.2411 43.9229V16.4389C48.2411 12.6441 45.1648 9.56787 41.3701 9.56787H20.7571C19.4921 9.56787 18.4667 10.5933 18.4667 11.8582V18.7291ZM21.9022 18.7291H29.9185C33.7132 18.7291 36.7895 21.8054 36.7895 25.6001V42.7777H44.8056V16.4389C44.8056 14.5415 43.2674 13.0034 41.3701 13.0034H21.9022V18.7291ZM10.4506 51.9389V22.1646H29.9185C31.8159 22.1646 33.354 23.7027 33.354 25.6001V51.9389H10.4506Z" fill="#269EB7"/>
            </svg>
          </div>
          <div class="ticket-text">
            Билеты формируются отдельно в процессе обработки материала. Таким образом, сочетание и порядок вопросов в тестах сервиса и в комиссиях Ростехнадзора или предприятия будут отличаться.
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="b-container">
    <div class="wrapper">
      <div class="description">
        <ul> 
          <li>Прохождение тестов  <span class="description-select"> бесплатно </span>  и не требует регистрации.</li>
          <li>Сервис предназначается для подготовки к аттестации в комиссиях Ростехнадзора или предприятий, которые эксплуатируют, обслуживают или проектируют опасные промышленные объекты (ОПО).</li>
          <li>Сервис добавляет или обновляет тесты по мере выхода и публикации новых редакций на официальном сайте Ростехнадзора. В названии теста указывается дата выхода (публикации) теста в официальном источнике. Устаревшие или отмененные редакции тестов перемещаются в архив.</li>
          <li>После прохождения теста, по желанию, возможно сформировать и распечатать протокол тестирования. Чтобы протокол был именным, необходимо указать имя тестируемого на стартовой странице сайта до начала прохождения теста и нажать кнопку выбора теста.</li>
          <li>В результате тестирования пользователю, в случае ошибок, указываются правильные ответы наряду с выбранными ответами.</li>
          <li>Билеты теста выбираются по номерам, или сервис сам может произвольно выбрать билет. Также возможно сформировать билет с произвольным набором вопросов.</li>
          <li>Сервис адаптирован для мобильных устройств.</li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script setup>
  import { storeToRefs } from 'pinia'
  // import { usePaginationStore } from '../stores/PaginationStore'
  import { useCategoryStore } from '../stores/CategoryStore'
  import { useTestsStore } from '../stores/TestsStore'
  import { useLoaderStore } from '../stores/Loader'
  // const store = usePaginationStore()
  const categorys = useCategoryStore()
  const tests = useTestsStore()
  const {getCategogys} = storeToRefs(categorys)
  const route = useRoute()
  const page = ref(route.params.page ? +route.params.page: 1)
  const loader = useLoaderStore()
  console.log(page.value)
  console.log(route)
  categorys.getApiCategorys({
    page: page.value > 1 ? page.value: '',
  })

  async function categoryUpdate({section}){
    console.log(section)
    if (section.test.length > 0 ){
      // tests.getApiTests({
      //   parentId: section.id
      // })
      navigateTo(`/area/${section.id}`)
    } else if (section.children.length > 0) {
      // categorys.getApiCategorys({
       
      //   parentId: section.id
      // })
      navigateTo(`/iter/1/group/${section.id}`)
      
    }  else {
      navigateTo(`/iter/1/group/${section.id}`)
      
    }
  }
  onMounted(() => {
    // console.log('я на клиенте')
    // // console.dir( window)
    // window.scrollTo(pageXOffset,0)
    // window.scrollY = 0
  })
</script>
<style lang="scss" scoped>
  .fon{
    background-color: var(--color-fon);
  }
  .sections{
    min-height: 100vh;
    &__block{
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      max-width: 1110px;
      margin: auto;
      @media (max-width: 480px) {
        justify-content:center;
      }
    }
    &__title{
      padding-top: 77px;
      max-width: 630px;
      font-family: 'Lato';
      font-style: normal;
      font-weight: 700;
      font-size: 36px;
      line-height: 40px;
      color: var(--color-Black_blue);
      @media (max-width: 350px) {
        padding-top: 20px;
        font-size: 16px;
        line-height: 19px;
      }
      &-select{
        color: var(--color-blue);
      }
      &-h2{
        margin-top: 50px;
        margin-bottom: 20px;
        font-family: 'Lato';
        font-style: normal;
        font-weight: 400;
        font-size: 24px;
        line-height: 40px;
        color: var(--color-Black_blue);
        @media (max-width: 330px) {
        display: none;
      }
      }
    }
  }
  .item{
    margin: 15px 0;
    padding: 0;
    height: 279px;
    background: #848EA8;
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    max-width: 350px;
    display: flex;
    justify-content: center;
    align-items: flex-end;
    background-position: center;
    background-size: cover;
    @media (max-width: 330px) {
        height: 234px;
        margin: 12px 0;
      }
    &-info{
      text-align:left ;
      height: 146px;
      background: var(--color-fon);
      border-radius: 6px;
      width: 90%;
      padding: 13px 26px;
      overflow: hidden;
      @media (max-width: 330px) {
        height: 120px;
        padding: 13px 22px;
      }
      &-title{
        height: 50%;
        font-family: 'Lato';
        font-style: normal;
        font-weight: 700;
        font-size: 24px;
        line-height: 29px;
        color: var(--color-Black_blue);
        overflow: hidden;
        text-overflow: ellipsis;
        @media (max-width: 330px) {
          font-size: 20px;
          line-height: 24px;
        }
      }
      &-discrabe{
        font-family: 'Lato';
        font-style: normal;
        font-weight: 400;
        font-size: 14px;
        line-height: 20px;
        color: #66727F;
        height: 53%;
        margin-top: 6px;
        overflow: hidden;
        text-overflow: ellipsis;
        @media (max-width: 330px) {
          font-size: 13px;
          line-height: 17px;
        }
      }
    }
    &:hover{
      cursor: pointer;
      .item-info{
        background: var(--color-blue);
        &-title{
          color:var(--color-white);
        }
        &-discrabe{
          color:var(--color-white);
        }
      }
    }
  }
  .font-weight-normal {
    font-weight: 300;
    text-align: center;
  }

.link{
  text-decoration: none;
  color: #204242;
  &:hover{
    text-decoration:underline;
    color: cadetblue;
    cursor: pointer;
  }
}
.service{
  display: flex;
  font-family: 'Lato';
  font-style: normal;
  justify-content: space-between;
  padding-bottom: 43px;
  flex-wrap: wrap;
  &-title{
    font-weight: 700;
    font-size: 32px;
    line-height: 40px;
    color: var(--color-blue);
    @media (max-width: 330px) {
      font-size: 20px;
      line-height: 40px;
    }
  }
  &-text{
    margin-top: 25px;
    font-weight: 400;
    font-size: 20px;
    line-height: 24px;
    color: var(--color-Black_blue);
    @media (max-width: 330px) {
      font-size: 16px;
      line-height: 19px;
      margin-top: 0px;
    }
    &-list{
      margin-top: 56px;
      font-weight: 700;
      color: var(--color-Black_blue);
      @media (max-width: 330px) {
        margin-top: 12px;
      }
    }
  }
  &-left-block{
    margin-bottom: 30px;
  }
  &-right-block{
    padding: 32px 9px 40px 27px;
    min-height: 335px;
    width: 348px;
    background:var(--color-blue);
    border: 1px solid var(--color-blue);
    border-radius: 6px;
    font-weight: 400;
    font-size: 18px;
    line-height: 22px;
    color: var(--color-white);
    @media (max-width: 992px) {
      width: 87vw;
      margin-left: 15px;
      min-height: 250px;
    }
    @media (max-width: 350px) {
      margin: 23px 0 0 0;
      width: 295px;
      padding: 22px 9px;
      font-size: 16px;
      line-height: 19px;

    }
  }
}
.ticket{
  min-height: 135px;
  display: flex;
  align-items: center;
  padding: 20px 0;
  @media (max-width: 350px) {
    flex-direction:column;
    justify-content: flex-start;
    align-items:flex-start;
    padding-left: 32px;
  }
  &-fon{
    background: #F1F7FF;
  }
  &-icon{
    margin-right: 30px;
  }
  &-text{
    font-weight: 400;
    font-size: 18px;
    line-height: 22px;
    color: var(--color-Black_blue);
    @media (max-width: 350px) {
      font-size: 16px;
      line-height: 19px;
    }
  }

}
.description{
  padding: 14px 0 35px 0px;
  min-height: 400px;
  font-weight: 400;
  font-size: 18px;
  line-height: 22px;
  color: #000000;
  @media (max-width: 350px) {
    font-size: 16px;
    line-height: 19px;
    ul{
      padding-left: 20px;
    }
  }
  &-select{
    color:var(--color-blue);
  }
}
 @media (min-width: 1024px) {
  
 }

</style>