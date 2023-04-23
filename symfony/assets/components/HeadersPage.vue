<template>
  <div class="page-header">
    <div class="container">
      <div class="wrapper">
        <div class="page-header-title">
          {{ title }}
        </div>
        <div class="page-header-title">
          {{ subTitle }}
        </div>
        <div class="page-header-navigation">
          <div class="page-header-navigation-crumbs">
            Тесты/
          </div>
          <Button
            title="Назад"
            @click="nextLink"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { RouterLink } from 'vue-router'
import Button from './ui/Button.vue'
export default {
  props: ['title', 'subTitle'],
  data() {
    return {
      isActive: false
    }
  },
  components:{
    Button
  },
  computed:{
   
  },
  methods: {
    nextLink(toLink){
      
      this.$router.go(-1)
    }
  }, 
  mounted() {
   
  }
} 

</script>


<style lang="scss" scoped>
  .page{
    &-header{
      width: 100%;
      background-color: #F1F7FF;
      padding: 32px 0;
      font-family: 'Lato';
      font-style: normal;
      &-title{
        font-weight: 700;
        font-size: 24px;
        line-height: 40px;
        margin-bottom: 25px;
      }
      &-navigation{
        display: flex;
        justify-content: space-between;
        &-crumbs{
          font-weight: 400;
          font-size: 12px;
          line-height: 14px;
          color: var(--color-blue);
        }
        
      }
    }}
 
</style>