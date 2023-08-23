<template>
  <div class="loader-cont"
    v-if="loader.isLoader"
  >
    <div class="loader-container">
      
        <div class="loader">
        </div>
      
    </div>
  </div>
</template>
<script setup>
  import { useLoaderStore } from '../../stores/Loader'
  const loader = useLoaderStore()
</script>
<style lang="scss" scoped>
.loader-cont{
  position: relative;
  height: 1px;
  z-index: 230;
}
.loader-container{
  position: absolute;
  width: 100%;
  height: 100vh;
  background-color: rgba(101, 112, 112, 0.432);
  display: flex;
  justify-content: center;
  align-items: center;
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
