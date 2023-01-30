<template>
   
</template>
 
<script>
 import TestQuestion from '../components/TestQuestionRadio.vue'
 import { mapGetters, mapActions, mapMutations} from "vuex"
 export default {
   components: {
     TestQuestion,
   },
   data() {
     return {
       count: 0,
         //  testName:''
       
     }
   },
    computed: mapGetters(["allPosts"]),
   
     methods: { 
      ...mapActions(["fetchPost"]),
      ...mapMutations([])
    },
     async mounted(){
      this.fetchPost()
     }
   
 } 
 
</script>
<style lang="scss" scoped>
 @media (min-width: 1024px) {
  
 }
</style>