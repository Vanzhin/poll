<template>
  <div class="">
    <div class="tests__block conteiner">
      <h4>Сервис онлайн тестирования по промышленной безопасности приветствует вас!</h4>
    </div>
    <div class="tests__block conteiner">
    <div class="sections__block row" >
      <div class="card shadow col-sm-12 col-md-6 col-lg-4 col-xs-2 item"
        v-for="section in sections" 
        :key="section.id"
      >
     
        <RouterLink :to="{ name: 'chapter', params: { id: section.id } }"
        class="link">
          <div class="card-header flex-shrink-1">
              <h5 class="my-0 font-weight-normal">{{ section.title }}</h5>
          </div>
          <div  class="img">
           
          </div>
          <!-- <picture>
            <source media="(min-width: 601px)" srcset="/card/_prom.jpg">   
            <img class="tableimg card-img-top" 
              :src="(url+section.image)" 
              :alt="section.title">
          </picture> -->
          <div class="card-body">
            <h6>{{ section.information }}</h6>
          </div> 
        </RouterLink>
      </div>
       
        
      </div>
    </div> 
         
     
  </div>
</template>
 
<script>
import TestQuestion from '../components/TestQuestion/TestQuestionRadio.vue'
import { RouterLink } from 'vue-router'
export default {
  components: {
  
  },
  data() {
    return {
      count: 0,
      //  testName:''
      //  url:'http://test2-open/assets/img/',
      urlCss: 'url(http://localhost:8080/build/img/',
      urlCss: 'url(../img/'
      
    }
  },
  computed:{
    sections () {
      return this.$store.getters.getSections
    },
    },
    methods: {
    
    }
    // mounted(){
    // }
  
} 
 
</script>
<style lang="scss" scoped>
  .sections__block{
    display: flex;
    flex-wrap: wrap;
  }
  .item{
    margin-bottom: 5px;
    padding: 5px;
    height: 250px;
    &:nth-child(1) .img{
    background-image: url(../img/_prom.jpg);
    }
    &:nth-child(2) .img{
      background-image: url(../img/_electro5.jpg);
    }
    &:nth-child(3) .img{
      background-image: url(../img/_pb.jpg);
    }
    &:nth-child(4) .img{
      background-image: url(../img/_energo1.jpg);
    }
    &:nth-child(5) .img{
      background-image: url(../img/_naks.jpg);
    }
    &:nth-child(6) .img{
      background-image: url(../img/_ot.jpg);
    }
  }
  .font-weight-normal {
    font-weight: 300;
    text-align: center;
    
}
.img{
  
  width: 100%;
  height: 150px;
  background-size: cover;
 
}
.link{
  text-decoration: none;
  color: #204242;
  &:hover{
    text-decoration:underline;
    color: cadetblue;
  }
}
 @media (min-width: 1024px) {
  
 }
</style>