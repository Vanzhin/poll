<template>
  <div class="btn-group dropup">
    <button class="btn  dropdown-toggle" 
      @click="toggleMeny"
    >
      <div  class="title"> 
        {{ buttonTitle }}
      </div> 
      
    </button>
    <ul 
      class="dropdown-menu menu" 
      aria-labelledby="dropdownMenuButton1"
      :class="[classObject, classDropPozition]"
      v-if="listItem"
    >
      <li v-for="item in listItem"
        class="dropdown-item menu_li"
        @click="$emit('itemMenuClick', item)" 
        :key="item.id"
      >
        {{ item.title }}
      </li>
    </ul>
  </div>
</template>
<script setup>
  
  const props = defineProps(['buttonTitle', 'listItem', 'dropPozition'])
  
  const toggle = ref({toggle: false, click: 0})
  const classObject = computed(() => ({
    active: toggle.value.toggle,
  }))
  const classDropPozition = computed(() => {
    return {
      "drop_up": props.dropPozition === 'up',
      "drop_bottom": props.dropPozition === 'bottom'
    }

  })
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
    text-align: center;
  }
  .btn{
    margin-top: 10px;
    display: flex;
    flex-direction: row;
    justify-content: center;
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
  .drop{
    &_up{
      bottom: 40px;
    }
    &_bottom{
      top: 50px;
    }
  }
  .active{
    display: block;
  }
</style>
