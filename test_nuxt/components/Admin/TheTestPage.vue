<template>
  <div class="block">
    <div class="title">
      <h2>Тест: {{ tests.getTestTitle }}</h2>
    </div>
    <UiButtonBack />
    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item" role="presentation"
        v-for="(nav, index ) in navsActive" 
          :key="nav.title"
      >
        <NuxtLink class="nav-link tabs-fonts" :class="{active: nav.active}"  
          @click="activeTogge(index)"
          :to="{ path: nav.link}" >{{ nav.title }} {{ nav.count }}
        </NuxtLink>
        
      </li>
      
    </ul>
    
    <component :is="AdminTestTabsTickets" 
      v-if="layout === 'AdminTestTabsTickets'"
    />
    <component :is="AdminTestTabsSections" 
      v-else-if="layout === 'AdminTestTabsSections'"
    />
    <component :is="AdminTestTabsQuestions" 
      v-else-if="layout === 'AdminTestTabsQuestions'"
    />
  </div>
</template>
<script setup>
import { AdminTestTabsTickets, AdminTestTabsSections, AdminTestTabsQuestions } from '#components'
  import { useTestsStore  } from '@/stores/TestsStore'
  const tests = useTestsStore()
  const route = useRoute()
  const testId = ref(route.params.testId)
  const categoryId = ref(route.params.testId)
  const test = ref(null)
  const classObject = ref({
    active: true,
  })
  const url = ref(route.path)
  const navs = ref([])
  const navsActive = computed(()=>{
    // url.value = route.path
     return navs.value = [
        {
          title: `Вопросы `,
          link: 'questions',
          active: (new RegExp("questions", 'i')).test(url.value),
          count: tests.getTestQuestionCount
        },
        {
          title: `Билеты `,
          link: 'tickets',
          active: (new RegExp("tickets", 'i')).test(url.value),
          count: tests.getTestTicketCount
        },
        {
          title: `Секции `,
          link: 'sections',
          active: (new RegExp("sections", 'i')).test(url.value),
          count: tests.getTestSectionCount
        }
      ]
    
  })
  const layout = computed(()=>{
    const link = navsActive.value.find((nav)=>nav.active).link
    console.log('AdminTestTabs' + link.slice(0,1).toUpperCase() + link.slice(1,10))
    return 'AdminTestTabs' + link.slice(0,1).toUpperCase() + link.slice(1,10) 
  })

  function activeTogge(index) {
      navs.value =[ ...navs.value.map((nav, ind) => {
        index === ind ? nav.active = true: nav.active = false
        return nav
      })]
    }
  
  onMounted(async() => {
    if (!tests.getTestActive){
      await tests.getTestIdDb({id: route.params.testId})
    }
   
    
  })
</script>
<style lang="scss" scoped>
  .tabs-fonts{
    font-family: 'Lato';
    font-style: normal;
    font-weight: 700;
    font-size: 16px;
    line-height: 24px;
    color: #0B1F33;
  }
  .block{
    
    padding: 10px ;
  }
  .title{
    display: flex;
    justify-content: space-between;
    margin: 10px;
    & h2{
      font-family: "Lato";
      font-style: normal;
      font-weight: 700;
      font-size: 30px;
      line-height: 40px;
      color: var(--color-blue);
    }
  }
  .test{
    display: flex;
  }
  [class*="col-"] {
  padding-top: 7px;
  padding-right: 7px;
  padding-left: 7px;
  margin-bottom: 10px;
  }
  
@media (min-width: 1024px) {
 
}
</style>