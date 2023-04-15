<template >
  <MessageView/>
  <FormInfoXML/>
  <Headers/>
  <div class="fon">
    <div class="container">
      <div class="wrapper">
        <div class="head"></div>
      </div>
      <div class="wrapper">
        <RouterView />
      </div>
    </div>
  </div>
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
.fon{
  background: var(--color-fon);
}
.head{
  height: 85px;
}

</style>