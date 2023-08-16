<template>
  <div class="btn-group dropup">
    <button class="btn  dropdown-toggle"  data-bs-toggle="dropdown" aria-expanded="false"
    @click="toggleMeny"
    >
      <div class="">
          <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3.92536 6.8489H21.5414C23.1307 6.88486 23.1307 4.81387 21.5414 4.84983H3.92536C2.33608 4.8138 2.33608 6.88486 3.92536 6.8489Z" fill="#269EB7"/>
            <path d="M14.9586 11.8498H3.50818C2.47514 11.8139 2.47514 13.8849 3.50818 13.8489H14.9586C15.9917 13.8849 15.9917 11.8139 14.9586 11.8498Z" fill="#269EB7"/>
            <path d="M18.7202 18.8494H3.74657C2.39568 18.8494 2.39568 20.8494 3.74657 20.8494H18.7202C20.0711 20.8494 20.0711 18.8494 18.7202 18.8494Z" fill="#269EB7"/>
          </svg>
      </div>
    </button>
    <ul 
      class="dropdown-menu menu" 
      :class="classObject"
      aria-labelledby="dropdownMenuButton1"
      v-if="categorys.getCategoriesForDropDown"
    >
      <li 
        class="dropdown-item menu_li"
        v-for="category in categorys.getCategoriesForDropDown"
        :key="category.id"
        @click="categoryUpdate({section:category})"
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
      window.addEventListener('click', dropDownHeadClickVisible)
    }
  }
  function dropDownHeadClickVisible(){
    if (toggle.value.toggle && toggle.value.click > 0){
      toggle.value.toggle = false
      toggle.value.click =  0
      window.removeEventListener("click", dropDownHeadClickVisible);
    } else {
      toggle.value.click += 1
    }
  }
  if (!categorys.allСategories) {
    categorys.getCategorysDBFooter()
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
    margin-top: 0px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0 0 0 10px ;
    
    width: 24px;
    height: 100%;
    border: 0px solid var(--color-blue);
    border-radius: 0px;
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
      transform: scale(1.2);
    }
  }
  .menu{
    transform: translate(0px, 40px);
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
