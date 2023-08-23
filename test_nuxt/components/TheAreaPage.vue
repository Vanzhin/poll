<template>
  <Head>
    <Title>{{ categorys.getCategoryTitle }} | Амулет Тест</Title>
    <Meta name="description" :content="description" />
    <Meta name="robots" :content="categorys.getCategoryRobots"/>
    <link rel="canonical" :href="categorys.getCategoryCanonical"/>
  </Head>
  <div class="sections" >
    <UiLoaderView
      v-if="loader.isLoader"
    />
    <div class=""  v-else>
      <TheHeaderVsPage
        :title="categorys.getCategoryTitle"
        subTitle="Тесты"
      />
      <div class="tests__block">
        <div class="container">
          <div class="wrapper">
            <div
              v-for="(test ) in getTests" 
              :key="test.id"
              class="test__block"
            >
              <div
                v-if="test.questionCount > test.questionUnPublishedCount"
              >
              <!-- getIsAutchUser || index < 3 -->
                <div 
                  @click="testLink({test})"
                  v-if="true"
                >
                  <div class="test__card">
                    <div>
                      {{ test.title }}
                    </div>
                  </div>
                </div>
                <div class="test__card-limitation"
                  v-else
                  title="У Вас ограниченный доступ. Подпишитесь на нашу группу."
                >
                  {{ test.title }}
                </div>
              </div>
              <div
                v-else
              >
                <div class="test__card-development">
                  <div>
                    {{ test.title }}
                  </div>
                  <div style="color: red;">
                    Тест в разработке.
                  </div>
                  
                </div>
              </div>
            </div>
            <ThePagination />
            <div class="test__block-info">
              Дополнительная информация
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
              </svg>
            </div>
            <div class="b-container">
              <div class="wrapper">
                <div class="description">
                  {{ categorys.getCategorySeoDescription }}
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
  import { storeToRefs } from 'pinia'
  import { usePaginationStore } from '../stores/PaginationStore'
  import { useTestsStore } from '../stores/TestsStore'
  import { useLoaderStore } from '../stores/Loader'
  import { useCategoryStore } from '../stores/CategoryStore'
  import { useUserStore  } from '../stores/UserStore'
  import { useCrumbsStore } from '../stores/CrumbsStore'
  
  const crumbs = useCrumbsStore()
  const categorys = useCategoryStore()
  const tests = useTestsStore()
  const user = useUserStore()
  const {getTests} = storeToRefs(tests)
  const loader = useLoaderStore()
  const route = useRoute()
  const parentId = ref(+route.params.id)
  const iterNum = ref(+route.params.num)
  const page = ref(route.params.page ? +route.params.page: 1)
 
  const description = computed(() => {
    return `${categorys.getCategoryTitle}. ${categorys.getCategoryDescription}. ${user.getGlobalDescription}`
  })
  
  
  async function getCondition() {
    await tests.getApiTests({
      page: page.value > 1 ? page.value: '',
      parentId: parentId.value,
    })
    await crumbs.getCategoryCrumbsDB(parentId.value)
    crumbs.setCrumbsAddTests(parentId.value)
  }

  getCondition()
  
  //переход при выборе теста
  function testLink({ test }){
    tests.testActiveSave(test)
    navigateTo(`/test/${test.id}`)
  }
</script>

<style lang="scss">
  .sections{
    min-height: 100vh;
  }
  .tests{
    &-header{
      width: 100%;
      background-color: #F1F7FF;
      padding: 32px 0;
      font-family: 'Lato';
      font-style: normal;
      &-title{
        font-weight: 700;
        font-size: 24px;
        line-height: 40px;
        margin-bottom: 25px;
      }
      &-navigation{
        display: flex;
        justify-content: space-between;
        &-crumbs{
          font-weight: 400;
          font-size: 12px;
          line-height: 14px;
          color: var(--color-blue);
        }
        
      }
    }
    &__block{
      width: 100%;
      padding-top: 24px;
      background: var(--color-fon);
      @media (max-width: 350px) {
        padding-top: 0px;
      }
    }

  }
  .test__block{
    margin-bottom: 15px;
    & a{
      color: var(--color-blue);
      text-decoration: none;
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
        margin-top: 36px;
        padding-bottom: 29px;
      }
      &:hover{
        cursor: pointer;
      }
    }
  }
  .test__card, .test__card-limitation{
    background-color: #FFFFFF;
    padding: 0 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-width: 0;
    word-wrap: break-word;
    border: 1px solid #269EB7;
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: var(--color-blue);
    min-height: 52px;
    margin-top: 17px;
    @media (max-width: 350px) {
      margin-top: 15px;
    }
  }
  .test__card-limitation{
    border: 1px solid #E0E6EE;
    color: #E0E6EE;
    
  }
  .test__card:hover{
    background-color: #E4E9F0;
    cursor: pointer;
  }
  .test__card-development{
    background-color: #FFFFFF;
    padding: 0 10px;
    min-width: 0;
    border: 1px solid var(--color-red);
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: var(--color-blue);
    min-height: 52px;
  }

</style>