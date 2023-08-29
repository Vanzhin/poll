<template>
  <div
    class="item__block"
  >
    <div class="">
      <div class="item__card" >
        <div class="item__card-block">
          <div class="item__card__img">
            <img :src="item.image" alt="" class="item__card__img"
              v-if="item.image"
            >
          </div>
          <div class="item__card-title">
            {{ item.id }} - {{ item.title }}
          </div>
        </div>
        <div class="item__card-block-button">
          <div class="item__card-block-button-flex" >
            <div class=" btn-center"
              title="Редактировать"
              @click.stop="editCategory"
            >
              <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M18.2407 6.75552L17.2857 5.71353C16.9165 5.31072 16.2924 5.27879 15.884 5.64183L14.3216 7.03088L16.6198 9.56793L18.1714 8.17538C18.5854 7.80377 18.6166 7.16567 18.2407 6.75552ZM5.5 14.8737L13.2006 8.02756L15.5035 10.5699L7.90674 17.388H5.5V14.8737ZM19.3465 5.74204L18.3915 4.70005C17.4686 3.69303 15.9083 3.61321 14.8874 4.5208L4.33557 13.9019C4.12212 14.0916 4 14.3636 4 14.6492V17.888C4 18.4403 4.44772 18.888 5 18.888H8.09822C8.34478 18.888 8.58266 18.7969 8.76616 18.6322L19.1733 9.29171C20.2084 8.36268 20.2863 6.76743 19.3465 5.74204ZM20 20.3847H4V21.8847H20V20.3847Z" fill="#269EB7"/>
              </svg>
            </div>
           
            <div class=" btn-center"
              title="Добавить вложенную категорию"
              @click.stop="createChildrenCategory"
              v-if="item.test && item.test.length === 0"
            >
              <svg width="16" height="21" viewBox="0 0 16 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M14 19.3892H2C1.72386 19.3892 1.5 19.1653 1.5 18.8892V2.88916C1.5 2.61302 1.72386 2.38916 2 2.38916H9.47374C9.60391 2.38916 9.72895 2.43992 9.82228 2.53066L14.3485 6.93119C14.4454 7.02533 14.5 7.15464 14.5 7.28969V18.8892C14.5 19.1653 14.2761 19.3892 14 19.3892ZM0 2.88916C0 1.78459 0.895431 0.88916 2 0.88916H9.47374C9.99442 0.88916 10.4946 1.09222 10.8679 1.45517L15.3942 5.8557C15.7815 6.23226 16 6.74949 16 7.28969V18.8892C16 19.9937 15.1046 20.8892 14 20.8892H2C0.895431 20.8892 0 19.9937 0 18.8892V2.88916ZM7.25 15.8892V12.6392H4V11.1392H7.25V7.88916H8.75V11.1392H12V12.6392H8.75V15.8892H7.25Z" fill="#269EB7"/>
              </svg>
            </div>
            <div class=" btn-center"
              title="Добавить тест"
              @click.stop="createChildrenTest"
              v-if="item.children && item.children.length === 0"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive" viewBox="0 0 16 16">
                <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1V2zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1v2h14V2zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
              </svg>
            </div>
            <div class=" btn-center" 
              title="Удалить"
              @click.stop="deleteVisibleConfirm"
            >
              <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.99782 6.21661C8.20421 4.87507 9.35852 3.88477 10.7158 3.88477H13.2842C14.6415 3.88477 15.7958 4.87507 16.0022 6.21661L16.105 6.88472H17.4955H19L19 6.88476H20V8.38476H18.8833L18.0486 19.1174C17.9271 20.6793 16.6242 21.8847 15.0576 21.8847H8.94239C7.37576 21.8847 6.07291 20.6793 5.95143 19.1174L5.11667 8.38477H4V6.88477H5L5 6.88472H6.50453H7.89503L7.99782 6.21661ZM14.5873 6.88472H9.41268L9.48037 6.44469C9.57419 5.8349 10.0989 5.38477 10.7158 5.38477H13.2842C13.9011 5.38477 14.4258 5.8349 14.5196 6.44469L14.5873 6.88472ZM6.6212 8.38477L7.44691 19.001C7.50765 19.782 8.15908 20.3847 8.94239 20.3847H15.0576C15.8409 20.3847 16.4923 19.782 16.5531 19.001L17.3788 8.38476L6.6212 8.38477Z" fill="#FF3F3F"/>
              </svg>
            </div>
          </div>
        </div>
      </div>
            
    </div>
    
  </div>
</template>
<script setup>
  import { useCategoryStore } from '../../stores/CategoryStore'
  import { useConfirmStore } from '../../stores/ConfirmStore'
  const categorys = useCategoryStore()
  const confirm = useConfirmStore()
  const props = defineProps(['item', 'index'])
  const route = useRoute()
  const page = ref(route.params.page ? +route.params.page: 1)
  const id = ref( null)
    
   
 
  
    function img(item) {
      const img = item ? item.slice(0, 4) + item.slice(5, item.length) : ''
      return img
    }
    
    function editCategory(){
      categorys.setCategory(props.item)
      navigateTo(`/admin/categorys/${props.item.id}/edit`)
      
    }
    async function  deleteCategoty(){
      console.log('Удаляю категорию № - ',props.item)
      categorys.deleteCategoryDb({
        id: props.item.id, 
        parentId: categorys.getCategoryParendId, 
        page:  page.value,
      })
      
    }
    function deleteVisibleConfirm(){

      confirm.setConfirmMessage("При удалении раздела, так же будут удалены все его внутренние области. Вы, действительно хотите это сделать?")
      let timerId = setInterval(() => {
        if (confirm.getConfirmAction) {
          clearInterval(timerId)
          if (confirm.getConfirmAction === "yes"){deleteCategoty()}
        }
      }, 200);
     }

    function createChildrenCategory(){
      navigateTo(`/admin/categorys/${props.item.id}/create`)
    }
    function createChildrenTest(){
      navigateTo(`/admin/categorys/${props.item.id}/test/create`)
    }


</script>
<style lang="scss" scoped>
  .item{
    &__block{
    margin: 10px 10px;
    position: relative;
  }
    
  }
  .item__card{
    background-color: #FFFFFF;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-width: 0;
    word-wrap: break-word;
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    flex-wrap: wrap;
    position: relative;
    &__img{
      height: 138px;
      width: 235px;
      margin-right: 15px;
    }
    &__child{
      height: 100px;
      background-color: #FFFFFF;
    }
    &__parend{
      height: 50px;
      background-color: #FFFFFF;
    }
    &-block{
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      max-width: 84%;
      &-button{
        height: 138px;
        &-flex{
          display: flex;
          align-items: flex-end;
          height: 100%;
        }
      }
    }
    &-title{
      font-family: 'Lato';
      font-style: normal;
      font-weight: 700;
      font-size: 20px;
      line-height: 24px;
      max-width: 68%;
    }
    
  }
 .item__card:hover{
    outline: 1px solid #269EB7;
    cursor: pointer;
  }
  .btn-center{
    height: 24px;
    width: 24px;
    margin-left: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #269EB7;
    &:hover{
      background-color: rgba(68, 151, 199, 0.342);
    }
  }  

</style>
