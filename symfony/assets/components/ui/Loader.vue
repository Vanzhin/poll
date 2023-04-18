<template>
  <div class="cont"
    v-if="getIsLoaderStatus"
  >
    <div 
      class="container-loader"
    >
      <div class="loader">
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  computed:{ 
      ...mapGetters(["getIsLoaderStatus"]),
    },
}
</script>

<style lang="scss" scoped>
  .cont{
    position: relative;
  }
  .container-loader{
    width: 100%;
    height: 93vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    z-index: 30;
    background-color: var(--color-fon);
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
    to {
        transform: rotate(360deg) scale(1.4);
    }
}
</style>
