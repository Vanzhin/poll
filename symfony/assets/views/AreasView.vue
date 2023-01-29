<template>
   <div class="">
    <div class="tests__block">
      <h4>{{ sectionTitle.title }}</h4>
      <h5>Выберите область аттестации:</h5>
    </div>
    <div class="tests__block">
      <div
        v-for="area in areas" 
        :key="area.id"
        class="test__block"
      >
        
        <div class="test__card">
          <RouterLink :to="{ name: 'area', params: { id: area.id } }">
            
              {{ area.id }} - {{ area.title }}
            
          </RouterLink>
      
        </div>
        
      </div>
    </div> 
         
     
  </div>
</template>
 
<script>
  
  export default {
    components: {
      
    },
    data() {
      return {
        count: 0,
          //  testName:''
        
      }
    },
    computed:{
      sectionTitle () {
        console.log(this.$route.params.id)
        console.log(this.$store.getters.getSectionTitle(this.$route.params.id))
        return this.$store.getters.getSectionTitle(this.$route.params.id)
      },
      areas () {
        console.log("areas")
        console.log(this.$store.getters.getAreas)
        return this.$store.getters.getAreas
      },
    },
    methods: {
      
    }
      // mounted(){
      // }
   
  } 
 
</script>
<style lang="scss" scoped>
 @media (min-width: 1024px) {
  
 }
</style>