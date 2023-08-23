<template>
  <div class="page-header">
    <div class="container">
      <div class="wrapper">
        <h1 class="page-header-title">
          {{ title }}
        </h1>
        <h2 class="page-header-title">
          {{ subTitle }}
        </h2>
        <div class="page-header-navigation">
          <div class="page-header-navigation-crumbs">
             <div
              v-for="(crumb, ind) in crumbs.getCrumbs" 
              :key="crumb.title"
            >
              <div class="page-header-navigation-crumbs-link"
                v-if="ind < crumbs.getCrumbsLendch"
                @click="crumbsLinkClick(crumb)"
                :title="crumb.link"
              >
                {{ crumb.title }}
              </div>
              <div class=""
                v-else
                :title="crumb.link"
              >
                {{ crumb.title }}
              </div>
             </div>
          </div>
          <UiButton
            title="Назад"
            @click="backLink"
          /> 
        </div>
      </div>
    </div>
  </div>
  
</template>
<script setup>
  import { useCrumbsStore } from '../stores/CrumbsStore'
  
  const props = defineProps(['title', 'subTitle']);
  
  const router = useRouter();
  const  crumbs = useCrumbsStore()
  
  function crumbsLinkClick(crumb){
    navigateTo(`${crumb.link}`)
  }
  
  function backLink(){
    if (crumbs.getCrumbsLendch > 0){
      navigateTo(`${crumbs.getCrumbs.at(-2).link}`)
    } else {
      router.back()
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
      @media (max-width: 330px) {
        padding: 23px 0;
      }
      &-title{
        font-weight: 700;
        font-size: 24px;
        line-height: 40px;
        margin-bottom: 25px;
        @media (max-width: 330px) {
          font-size: 16px;
          line-height: 19px;
          margin-bottom: 13px;
        }
      }
      &-navigation{
        display: flex;
        justify-content: space-between;
        &-crumbs{
          display: flex;
          font-weight: 400;
          font-size: 12px;
          line-height: 14px;
          color: var(--color-blue);
          display: flex;
          flex-wrap: wrap;
          &-link{
            color:var(--color-blue);
            text-decoration:none;
            &:hover{
              cursor: pointer;
              color: rgb(20, 65, 211);
            }
          }

        }
        
      }
    }}
 
</style>