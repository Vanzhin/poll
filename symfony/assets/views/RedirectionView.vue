<template>
  
</template>
 
<script>
  import { mapGetters, mapActions} from "vuex"
  export default {
    components: {
    },
    data() {
      return {
        count: 0,
        email:'',
        password:'',
        checkbox:'false'
      }
    },
    computed:{
      ...mapGetters(["getPageName"])
    },
    methods: {
      ...mapActions(["getAuthRefresh",]),
      
    },
   async mounted(){
      if (this.$route.params.rtoken !== "" ) {
        await this.getAuthRefresh(this.$route.params.rtoken)
        console.log(this.getPageName)
        this.$router.push( this.getPageName===""? "/": this.getPageName)
      }

    }
  } 
</script>

<style lang="scss" scoped>

@media (min-width: 1024px) {
  
}
</style>