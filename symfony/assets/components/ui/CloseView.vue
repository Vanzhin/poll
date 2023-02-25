<template>
  <div class="sub-block">
    <RouterLink :to="{ name: 'home'}" class="routerLink">
      <div class="sub-close">
        <i class="bi bi-x-lg sub-close" title="Закрыть"></i> 
      </div> 
    </RouterLink>
  </div>
</template>
<script>
import { RouterLink } from 'vue-router'
export default {
}
</script>

<style lang="scss" scoped>
  .sub{
  &-block{
    display: flex;
    justify-content: end;
  }
  &-close{
    cursor: pointer;
    transition: all 0.5s ease-out;
    margin-left: 5px;
    color: rgb(12, 12, 12);
    &:hover{
      
      transform: scale(1.25);
    }
    
  }
}
</style>
