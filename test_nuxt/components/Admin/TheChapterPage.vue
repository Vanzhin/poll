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
                v-if="categorys.getCategorysIs"
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
          <div class="create_test">
              <h4>Создайте раздел.</h4>
              <div class="btn-center"
                title="Добавить новую категорию"
                @click.stop="createCategory"
              >
                <svg width="36" height="37" viewBox="0 0 36 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M30.4023 18.8252C30.4023 25.9054 24.6627 31.645 17.5825 31.645C10.5023 31.645 4.7627 25.9054 4.7627 18.8252C4.7627 11.745 10.5023 6.00537 17.5825 6.00537C24.6627 6.00537 30.4023 11.745 30.4023 18.8252ZM31.9023 18.8252C31.9023 26.7338 25.4911 33.145 17.5825 33.145C9.6739 33.145 3.2627 26.7338 3.2627 18.8252C3.2627 10.9166 9.6739 4.50537 17.5825 4.50537C25.4911 4.50537 31.9023 10.9166 31.9023 18.8252ZM24.7419 19.899H10.4221V17.751H24.7419V19.899Z" fill="#269EB7"/>
                  <path d="M16.5088 11.6646L16.5088 25.9844L18.6567 25.9844L18.6567 11.6646L16.5088 11.6646Z" fill="#269EB7"/>
                </svg>
              </div>
            </div>
          <div 
            class="create_test"
            v-if="categoryId"
          >
            <h4>Или создайте тест.</h4>
            <div class="btn-center"
              title="Добавить новый тест"
              @click.stop="createChildrenTest"
             
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-archive" viewBox="0 0 16 16">
                <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1V2zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1v2h14V2zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
              </svg>
            </div>
          </div>
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
  const categoryId = ref(route.params.id ? +route.params.id: null)
  
  function createCategory(){
    navigateTo(`/admin/categorys/${categoryId.value || 0}/create`)
  }
  function createChildrenTest(){
    navigateTo(`/admin/categorys/${categoryId.value}/test/create`)
  }

  async function categoryRoute(item){
    console.log(item)
    if (('test' in item) && (item.test.length > 0)) {
      navigateTo(`/admin/categorys/${item.id}/tests`)
      return
    } 
    categorys.categorys = item.children
    navigateTo(`/admin/categorys/${item.id}`)
  }

  onMounted(async() => {
    console.log('запрос категорий')
    
    const user = useUserStore()
    await user.setTokenIsLocalStorage()
    await categorys.getApiCategorys({
      admin: true, 
      page: page.value > 1 ? page.value: '',
      parentId: categoryId.value,
      loading: !(categoryId.value && categorys.getCategorys.length > 0)
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
      justify-content: flex-end;
    }
    
    transition: all 0.1s ease-out;
    &:hover{
      cursor: pointer;
      transform: scale(1.15);
    }
  }
  .create_test{
    margin-top: 30px;
    width: 90%;
    display: flex;
    justify-content: space-between;
  }
  .btn-center{
    height: 40px;
    width: 40px;
    margin-left: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #269EB7;
    &:hover{
      transform: scale(1.25);
      cursor: pointer;
    }
  }  
 @media (min-width: 1024px) {
  
 }
</style>