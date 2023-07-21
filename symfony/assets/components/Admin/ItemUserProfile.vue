<template>
 
  <div class="cont-question">
    <div class="grup_label">
      <div  class="label" v-if="profile.firstName"><b>Имя:</b>  <p>{{ profile.firstName }}</p></div> 
      <div class="label"  v-if="profile.lastName"><b>Фамилия:</b>  <p>{{ profile.lastName }}</p></div>
      <div class="label"  v-if="profile.middleName"><b>Отчество:</b> <p>{{ profile.middleName }}</p> </div>
    </div>
    <p  v-if="profile.citizenship"> <b>Гражданство:</b> {{ profile.citizenship }}</p>
    <p  v-if="profile.diploma"> <b>Диплом:</b> {{ profile.diploma }}</p>
    <p  v-if="profile.educationLevel"><b>Образование:</b>  {{ profile.educationLevel }}</p>
    <p  v-if="profile.snils"> <b>СНИЛС</b>: {{ profile.snils }}</p>
    <div class="grup_label">
      <div class="label" v-if="profile.department"><b>Отдел:</b>  {{ profile.department }}</div>
      <div class="label" v-if="profile.position"><b>Должность:</b>  {{ profile.position }}</div>
    </div>
</div>
   
      
    
 
</template>

<script>


import { mapGetters, mapActions, mapMutations} from "vuex"

export default {
  props:['profile', 'index'],
  components: {
    
   
  },
  data() {
    return {
     
    }
  },
  computed:{
    ...mapGetters([
      "getTest",
      
    ]),
  },
  methods: {
    ...mapActions([
      "deleteQuestionDb", 
      
    ]),
    
    
  },
  async created(){
   
  },
} 

</script>
<style lang="scss" scoped>
 
  
  .cont-question{
    padding: 10px 10px 0 10px;
    position: relative;
  }
  .grup_label{
    display: flex;
  }
  .label{
    display:block;
    margin: 0 10px;
  }
  
@media (min-width: 1024px) {
 
}
</style>
