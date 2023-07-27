<template>
  <!-- <MyConfirm/> -->
  <uiMessageView/>
  <div class="backgraund">
    
        <slot />
  </div>
</template>
<script setup>


</script>
<style lang="scss" scoped>
.backgraund{
  width: 100%;
  height: 100%;
  min-height: 80vh;
  background-color: #f0eeff;
  padding-top: 1px;
}
</style>