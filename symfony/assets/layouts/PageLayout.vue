<template>
  <MessageView/>
  <FormInfoXML/>
  <Headers/>
  
  <div class="container">
    <div class="wrapper">
      <div class="head"></div>
    </div>
    <div class="wrapper">
      <RouterView />
    </div>
  </div>
</template>
<script>
import { RouterLink, RouterView } from 'vue-router'
import FormInfoXML from "../components/ui/FormInfoXML.vue"
import MessageView from "../components/ui/MessageView.vue"
import Headers from '../components/Headers.vue'
export default {
  components: {
    Headers,
    FormInfoXML,
    MessageView
  },
 

} 
</script>
<style lang="scss">
.head{
  height: 80px;
}

</style>