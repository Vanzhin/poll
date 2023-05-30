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
              v-for="(crumb, ind) in crumbs" 
            >
              <div class="page-header-navigation-crumbs-link"
                v-if="ind < crumbs.length-1"
                @click="crumbsLinkClick(crumb)"
              >
                {{ crumb.title }}
              </div>
              <div class=""
                v-else
              >
                {{ crumb.title }}
              </div>
             </div>
          </div>
          <Button
            v-if="winWidth > 350"
            title="Назад"
            @click="backLink"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations} from "vuex"
import { RouterLink } from 'vue-router'
import Button from './ui/Button.vue'
export default {
  props: ['title', 'subTitle'],
  data() {
    return {
      isActive: false,
      
    }
  },
  components:{
    Button
  },
  computed:{
    crumbs(){
      return this.$store.getters.getCrumbs
    },
    winWidth(){
      return window.screen.width
    }

  },
  methods: {
    ...mapActions([
      "setCrumbsLength"
    ]),
    backLink(toLink){
      const crumb = this.crumbs.slice(-2,-1)[0]
      this.$router.push({name: crumb.name, params: crumb.params })
      this.setCrumbsLength({len:this.crumbs.length-1})
    },
    crumbsLinkClick(crumb){
      this.setCrumbsLength({len:crumb.iter})
      this.$router.push({name: crumb.name, params: crumb.params })
    }
  }, 
  mounted() {

  
    // this.winWidth = window.visualViewport.width
  
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