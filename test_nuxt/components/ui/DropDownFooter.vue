<template>
  <div class="btn-group dropup">
    <button class="btn  dropdown-toggle"  data-bs-toggle="dropdown" aria-expanded="false"
      @click="toggleMeny"
    >
      <div  class="title"> 
        Все разделы тестирования
      </div> 
      <div>
        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M9.87954 8.51725C9.60121 8.23826 9.60112 7.78666 9.87935 7.50757V7.50757C10.1586 7.22745 10.6122 7.22731 10.8917 7.50726L14.6709 11.2936C15.0605 11.6839 15.0605 12.3161 14.6709 12.7064L10.8917 16.4927C10.6122 16.7727 10.1586 16.7726 9.87935 16.4924V16.4924C9.60112 16.2133 9.60121 15.7617 9.87954 15.4828L13.3541 12L9.87954 8.51725Z" fill="#269EB7"/>
        </svg>
      </div>
    </button>
    <ul 
      class="dropdown-menu menu" 
      aria-labelledby="dropdownMenuButton1"
      :class="classObject"
      v-if="categorys.getCategoriesForDropDown"
    >
      <li v-for="category in categorys.getCategoriesForDropDown"
        class="dropdown-item menu_li"
        @click="categoryUpdate({section:category})" 
        :key="category.id"
      >
        {{ category.title }}
      </li>
    </ul>
  </div>
</template>
<script setup>
  import { useCategoryStore } from '../../stores/CategoryStore'
  const categorys = useCategoryStore()
  const toggle = ref({toggle: false, click: 0})
  const classObject = computed(() => ({
    active: toggle.value.toggle,
  }))
  function toggleMeny(){
    toggle.value.toggle = !toggle.value.toggle
    toggle.value.click =  0
    if (toggle.value.toggle){
      window.addEventListener('click', dropDownFooterClickVisible)
    }
  }

  function dropDownFooterClickVisible(){
    if (toggle.value.toggle && toggle.value.click > 0){
      toggle.value.toggle = false
      toggle.value.click =  0
      window.removeEventListener("click", dropDownFooterClickVisible);
    } else {
      toggle.value.click += 1
    }
  }
</script>   

<style lang="scss" scoped>
  .dropup .dropdown-toggle::after{
    border: 0;
  }
  .title{
    margin-top: 0;
    font-size: 14px;
    line-height: 30px;
  }
  .btn{
    margin-top: 10px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    padding: 0 0 0 10px ;
    gap: 10px;
    width: 246px;
    height: 36px;
    border: 1px solid var(--color-blue);
    border-radius: 6px;
    color: var(--color-blue);
    background-color: rgba(255, 255, 255, 0);
    font-family: 'Lato';
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: var(--color-blue);
    &:hover{
      cursor: pointer;
      background: var(--color-blue);
      .title{
        color: var(--color-white);
      };
      path{
        fill:var(--color-white);
      }
    }
  }
  .menu{
    bottom: 40px;
    width: 240px;
    background: var(--color-blue);
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    max-height: 300px;
    overflow-y: auto;
   &-hr{
    margin:10px 15px ;
    
    border-top: 1px solid var(--color-white);
    color: var(--color-white);
    opacity: 1;
   }
    &_li{
      word-wrap: break-word;
      white-space: normal;
      font-family: 'Lato';
      font-style: normal;
      font-weight: 400;
      font-size: 16px;
      line-height: 24px;
      color: var(--color-white);
    &:hover{
      &svg {
        fill: var(--color-blue);
      }
      cursor: pointer;
      color: var(--color-blue);
    }}
  }
  .active{
    display: block;
  }
</style>
