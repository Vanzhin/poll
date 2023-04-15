<template>
  <MessageView/>
  <MyConfirm/>
  <div class="backgraund">
    <RouterView />
  </div>
</template>
<script>
import { RouterLink, RouterView } from 'vue-router'
import MessageView from "../components/ui/MessageView.vue"
import MyConfirm from '../components/ui/MyConfirm.vue'
export default {
  components: {
    MessageView,
    MyConfirm
  },
} 
</script>
<style lang="scss" scoped>
.backgraund{
  width: 100%;
  height: 100%;
  min-height: 80vh;
  background-color: rgb(163 156 156);
  padding-top: 1px;
  
}
</style>