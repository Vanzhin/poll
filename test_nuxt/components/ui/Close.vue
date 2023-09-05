<template>
  <div class="sub-block">
    <NuxtLink :to="toLink" class="routerLink">
      <div class="sub-close" title="Закрыть">
        <svg xmlns="http://www.w3.org/2000/svg"  width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
          <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
        </svg>
      </div> 
    </NuxtLink>
  </div>
</template>
<script>
  export default {
    props: ['toLink',],
  }
</script>
<style lang="scss" scoped>
  .sub{
    &-block{
      display: flex;
      justify-content: flex-end;
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
