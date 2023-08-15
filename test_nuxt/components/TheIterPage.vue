<template>
  <div>
    <Head>
      <Title>{{ categorys.getCategoryTitle }} | Амулет Тест</Title>
      <Meta name="description" :content="description" />
      <Meta name="robots" :content="categorys.getCategoryRobots"/>
      <link rel="canonical" :href="categorys.getCategoryCanonical"/>
    </Head>
    <UiLoaderView
      v-if="loader.isLoader"
    />
      <div class="sections" 
        v-else
      >
        <TheHeaderVsPage
          :title="categorys.getCategoryTitle"
        />
        <div class="fon">
          <div class="container">
            <div  class="wrapper">
              <div class="tests__block"
                v-if="categorys.categorys"
              >
                <div
                  v-for="(area) in getCategogys" 
                  :key="area.id"
                >
                  <div class="test__card"
                    v-if="true"
                    @click="categoryUpdate({section: area })"
                  >
                    <div>
                      {{ area.title }}
                    </div>
                    <div>
                      <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.50356 9.39524C9.22523 9.11625 9.22515 8.66465 9.50337 8.38556C9.78263 8.10544 10.2363 8.1053 10.5157 8.38525L14.2949 12.1715C14.6845 12.5619 14.6845 13.1941 14.2949 13.5844L10.5157 17.3707C10.2363 17.6507 9.78263 17.6505 9.50337 17.3704C9.22515 17.0913 9.22523 16.6397 9.50356 16.3607L12.9781 12.878L9.50356 9.39524Z" fill="#269EB7"/>
                      </svg>
                    </div>
                  </div>
                  <div class="test__card-limitation"
                    v-else
                    title="У Вас ограниченный доступ. Подпишитесь на группу."
                  >
                    {{ area.title }}
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
              <div class="tests__block min-heig"
                v-else
              > 
                Данная категория в разработке.
              </div> 
              
            </div>
          </div>
        </div>
      </div>
  </div>
</template>
<script setup>
  import { storeToRefs } from 'pinia'
  import { useCategoryStore } from '../stores/CategoryStore'
  import { useLoaderStore } from '../stores/Loader'
  import { useUserStore  } from '../stores/UserStore'
  import { useCrumbsStore } from '../stores/CrumbsStore'
  
  const crumbs = useCrumbsStore()
  const loader = useLoaderStore()
  const categorys = useCategoryStore()
  const user = useUserStore()
  const {getCategogys} = storeToRefs(categorys)
  const route = useRoute()
  
  const parentId = ref(+route.params.id)
  const iterNum = ref(+route.params.num)
  console.log(categorys)
  console.log(route.params)
  console.log(parentId)
  
  const page = ref(route.params.page ? +route.params.page: 1)
  console.log(page.value)
    
  
  const description = computed(() => {
    return `${categorys.getCategoryTitle}. ${categorys.getCategoryDescription}. ${user.getGlobalDescription}`
  })

  //переход при выборе категории
  async function categoryUpdate({section}){
    iterNum.value++
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
      navigateTo(`/iter/${iterNum.value}/group/${section.id}`)
      
    }  else {
      navigateTo(`/iter/${iterNum.value}/group/${section.id}`)
      
    }
  }

  async function getCondition() {
    await categorys.getApiCategorys({
    page: page.value > 1 ? page.value: '',
    parentId: parentId.value,
  })
    crumbs.getCategoryCrumbsDB(parentId.value)
    // if (crumbs.getCrumbsLendch >= 0){
    //   crumbs.addIteration({
    //     title:`/${categorys.getCategoryAlias}`,
    //     link: `/iter/${iterNum.value}/group/${parentId.value}`,
    //     active: false
    //   })
    //   crumbs.getCategoryCrumbsDB(parentId.value)
    // } else {
    //   crumbs.getCategoryCrumbsDB(parentId.value)
    // }

  }

  getCondition()
</script>
<style lang="scss" scoped>
.sections{
    min-height: 100vh;
  }
 .min-heig{
  min-height: 20vh;
}
.tests{
  &__block{
      width: 100%;
      padding-top: 24px;
      background: var(--color-fon);
      @media (max-width: 350px) {
         padding-top: 0px;
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

 @media (min-width: 1024px) {
  
 }
</style>