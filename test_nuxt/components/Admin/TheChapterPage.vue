<template>
  
  <div class="fon"
    v-if="!loader.isLoader"
  >

    <div class="container">
      <div class="wrapper">
        <div class="row">
          <div class="title">
            <h2>Разделы</h2>
            <div class="button-cont">
              <div class="button"
                title="Добавить новую категорию"
                @click.stop="createCategory"
              >
                <svg width="36" height="37" viewBox="0 0 36 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M30.4023 18.8252C30.4023 25.9054 24.6627 31.645 17.5825 31.645C10.5023 31.645 4.7627 25.9054 4.7627 18.8252C4.7627 11.745 10.5023 6.00537 17.5825 6.00537C24.6627 6.00537 30.4023 11.745 30.4023 18.8252ZM31.9023 18.8252C31.9023 26.7338 25.4911 33.145 17.5825 33.145C9.6739 33.145 3.2627 26.7338 3.2627 18.8252C3.2627 10.9166 9.6739 4.50537 17.5825 4.50537C25.4911 4.50537 31.9023 10.9166 31.9023 18.8252ZM24.7419 19.899H10.4221V17.751H24.7419V19.899Z" fill="#269EB7"/>
                  <path d="M16.5088 11.6646L16.5088 25.9844L18.6567 25.9844L18.6567 11.6646L16.5088 11.6646Z" fill="#269EB7"/>
                </svg>
              </div>
            </div>
          
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="tests__block"
          v-if="categorys.getCategorysIs">
          <AdminItemChapter
            v-for="(item, index) in categorys.getCategorys" 
            :key="item.id"
            :item="item"
            :index="index"
            @click.stop="categoryRoute(item)"
          />
        </div>
        <div class="tests__block"
          v-else>
          <h4>Создайте раздел.</h4>
        </div>

        <ThePagination />
      </div>
    </div>
  </div>
</template>
 
<script setup>
  
  import { useCategoryStore } from '../../stores/CategoryStore'
  import { useLoaderStore } from '../../stores/Loader'
  import { useUserStore  } from '../../stores/UserStore'
  const categorys = useCategoryStore()
  const loader = useLoaderStore() 
  const route = useRoute()
  const page = ref(route.params.page ? +route.params.page: 1)
  
  function createCategory(){
    navigateTo(`/admin/categorys/0/create`)
    // this.$router.push({name: 'adminCategoryCreate', params: {operation:"create", id: 0  } })
  }

  async function categoryRoute(item){
    if (this.getTests) {
      this.$router.push({name: 'adminTests', params: {id } })
      
      return
    } 
    this.$router.push({name: 'adminIter', params: { num: 1, id: id } })
    
  }

  onMounted(async() => {
    console.log('запрос категорий')
    const user = useUserStore()
    await user.setTokenIsLocalStorage()
    await categorys.getApiCategorys({
      admin: true, 
      page: page.value > 1 ? page.value: '',
    })
    
  })
    
   
  
 
</script>
<style lang="scss" scoped>
  .title{
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-top: 10px;
    padding: 64px 0 8px 0;
    & h2 {
      font-family: 'Lato';
      font-style: normal;
      font-weight: 700;
      font-size: 30px;
      line-height: 40px;
      color: var(--color-blue);
    }
  }
  .create{
    display: flex;
    align-items: center;
    &-plus{
      transform: scaleY(1.3);
    }
  }
  .button{
    margin-right: 20px;
    &-cont{
      display: flex;
      justify-content: end;
    }
    
    transition: all 0.1s ease-out;
    &:hover{
      cursor: pointer;
      transform: scale(1.15);
    }
  }
 @media (min-width: 1024px) {
  
 }
</style>