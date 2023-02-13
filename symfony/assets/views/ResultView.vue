<template>
  <ResultAutchView
    v-if="getIsAutchUser"
  />
  <ResultNoAutchView
    v-else
  />
   
</template>

<script>

import ResultAutchView from './ResultAutchView.vue'
import ResultNoAutchView from './ResultNoAutchView.vue'
import Loader from '../components/ui/Loader.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  components: {
    ResultAutchView,
    ResultNoAutchView
  },
  data() {
    return {
       
    }
  },
  computed:{
    ...mapGetters(["getIsAutchUser", ]),
    
  },
  methods: {
  
  },
  mounted(){
    window.scroll(0, 0);
  }
  
} 

</script>
<style lang="scss" scoped>

</style>
