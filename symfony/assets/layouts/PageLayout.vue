<template >
  <MessageView/>
  <FormInfoXML/>
  <Headers/>
  <div class="wrapper">
    <div class="head"></div>
  </div>
  <RouterView />
  
  <FooterBlock/>
</template>
<script>
import { RouterLink, RouterView } from 'vue-router'
import FormInfoXML from "../components/ui/FormInfoXML.vue"
import MessageView from "../components/ui/MessageView.vue"
import Headers from '../components/Headers.vue'
import FooterBlock from '../components/Footer.vue'
export default {
  components: {
    Headers,
    FormInfoXML,
    MessageView,
    FooterBlock
  },
 

} 
</script>
<style lang="scss">

.head{
  height: 85px;
}

</style>