<template>
  <div class="loader-container">
    <div class="loader-block">
      <div class="loader">
      </div>
    </div>
  </div>
</template>
<script setup>
 
</script>
<style lang="scss" scoped>
.loader-container{
  position: relative;
  width: 100%;
  height: 20vh;
}
.loader-block{
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  position: absolute;
  z-index: 30;
    
}
.loader {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  border: 3px dashed teal;
  animation: rotate 1s infinite linear;
}
@keyframes rotate {
  from {
      transform: rotate(0deg) scale(1);
  }
  50%{
    transform: rotate(360deg) scale(1.4);
  }
  to {
      transform: rotate(720deg) scale(1);
  }
}
</style>
