<template>
  <div 
    v-if="getIsLoaderQuestions"
    class="container-loader"
  >
    <div class="loader">
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  computed:{ 
      ...mapGetters(["getIsLoaderQuestions"]),
    },
}
</script>

<style lang="scss" scoped>
  .container-loader{
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    z-index: 30;
    background-color: #ced5d352;
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
