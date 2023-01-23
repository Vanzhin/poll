<template>
   <div class="container">
     <div class="wrapper">
         <h4>Эксплуатация оборудования кабельных линий электросетевого хозяйства потребителей</h4>
         <h5>Выберите вопрос</h5>
         <div class="block">
            <div
               v-for="question in questions" 
               :key="question.id"
            >
               <RouterLink :to="{ name: 'question', params: { id: question.id } }">
                  <div class="number" >
         
                     <p>{{ question.id }}</p>
                     
                  </div>
               </RouterLink>
            </div>
         </div> 
         
      </div>
   </div> 
 
   
 </template>
<script>
import { RouterLink } from 'vue-router'
export default {
   data() {
      return {
         count: 0
      }
   },
   computed:{
      questions () {
         return this.$store.getters.setQuestions
      },
   },
  
} 

</script>



<style scoped>
.container{
 
}
.wrapper{
   background-color: #ffffff;
   padding: 5px 0;
}
.block{
   width: 100%;
   display: flex;
   padding: 10px;
   margin: 0 10px;
   align-items: center;
   flex-wrap: wrap;
}
.number{
   width: 25px;
   height: 25px;
   border: 1px solid saddlebrown;
   margin: 3px;
   text-align: center;
   border-radius: 4px;
}
.number:hover{
      cursor: pointer;
      background-color: rgb(214, 202, 185);
   }
</style>
