<template >
  <MessageView/>
  <FormInfoXML/>
  <Headers/>
  <Loader/>
  <RouterView />
  <FooterBlock/>
</template>
<script>
import { RouterLink, RouterView } from 'vue-router'
import FormInfoXML from "../components/ui/FormInfoXML.vue"
import MessageView from "../components/ui/MessageView.vue"
import Headers from '../components/Headers.vue'
import FooterBlock from '../components/Footer.vue'
import Loader from '../components/ui/Loader.vue'
export default {
  components: {
    Headers,
    Loader,
    FormInfoXML,
    MessageView,
    FooterBlock,
  },
} 
</script>
<style lang="scss">

</style>