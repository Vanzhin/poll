<template>
  <div class="block">
    <div class="title">
      <div>
        <h2>Компания: </h2>
        <p>{{ companyName }}</p>
        <p>админ - {{ companys.getAdminCompany }}</p>
      </div>
      <UiButtonBack/>
    </div>
    
    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item" role="presentation"
        v-for="(nav, index ) in navsActive" 
          :key="nav.title"
      >
        <NuxtLink class="nav-link tabs-fonts" :class="{active: nav.active}"  
          @click="activeTogge(index)"
          :to="{ path: nav.link}" >{{ nav.title }} 
        </NuxtLink>
      </li>
    </ul>
    <AdminCompanyTabsStaff 
      v-if="layout === 'staff'"
    />
    <AdminCompanyTabsGroups 
      v-else-if="layout === 'groups'"
    />

  </div>
  
</template>


<script setup>
  import { useCompanyStore } from '../../stores/CompanyStore'
  import { usePaginationStore } from '@/stores/PaginationStore'
  
  const pagination = usePaginationStore()
  const companys = useCompanyStore()

  const route = useRoute()

  const companyId = ref( route.params.companyId)
  
  const classObject= ref( {
    active: true,
  })
  const url = ref( route.path)
  const navs= ref([])
        
  const layout = computed(()=>{
    const link = navsActive.value.find((nav)=>nav.active).link
    return  link
  })


  const companyName = computed(()=>{
    console.log(companys.getCompany)
    return companys.getCompany ? companys.getCompany.title : ''
  })
    
  const navsActive = computed(()=>{
    
    return navs.value=[
      {
        title: `Сотрудники `,
        link: 'staff',
        active: (new RegExp("staff", 'i')).test(url.value),
        // count: this.countQuestion
      },
      {
        title: `Группы `,
        link: 'groups',
        active: (new RegExp("groups", 'i')).test(url.value),
        // count: this.countTicket
      },
      
    ]
  })
  
  
  
  function activeTogge(index) {
    navs.value = [ ...navs.value.map((nav, ind) => {
      index === ind ? nav.active = true: nav.active = false
      return nav
    })]
  }
  
 
  async function created(){
    console.log(companys.getCompany)
    if (!companys.getCompany){
      console.log("получаю данные компании")
      await companys.getCompanyIdDB({id: +route.params.companyId})
    }
  }

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