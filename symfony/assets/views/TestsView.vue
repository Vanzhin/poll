<template>
  <Loader
    v-if="isLoader"
  />
  <div class=""  v-else>
    <HeadesPage
      :title="areaTitle"
      subTitle="Тесты"
    />
    <div class="tests__block">
      <div class="container">
        <div class="wrapper">
          <div
            v-for="(test, index) in tests" 
            :key="test.id"
            class="test__block"
          >
            <div
              v-if="test.questionCount > test.questionUnPublishedCount"
            >
              <div 
                @click="testLink(test)"
                v-if="getIsAutchUser || index < 3"
              >
                <div class="test__card">
                  <div>
                    {{ test.title }}
                  </div>
                </div>
              </div>
              <div class="test__card-limitation"
                v-else
                title="У Вас ограниченный доступ. Подпишитесь на нашу группу."
              >
                {{ test.title }}
              </div>
            </div>
            <div
              v-else
            >
              <div class="test__card-development">
                <div>
                  {{ test.title }}
                </div>
                <div style="color: red;">
                  Тест в разработке.
                </div>
                
              </div>
            </div>
          </div>
          <div class="test__block-info">
            Дополнительная информация
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
            </svg>

          </div>
        </div>
      </div>
    </div> 
    <Pagination
        type="getTestsDB"
      />
  </div>
</template>
<script>
import { RouterLink } from 'vue-router'
import { mapGetters, mapActions, mapMutations} from "vuex"
import Loader from '../components/ui/LoaderView.vue'
import Pagination from '../components/Pagination.vue'
import HeadesPage from '../components/HeadersPage.vue'
import crumbsTitle from '../utils/crumbs.js'
export default {
  components: {
    Loader,
    Pagination,
    HeadesPage
  },
  data() {
    
    return {
      isLoader: false,
    }
  },
  computed:{
    ...mapGetters([
      "getIsAutchUser",
      "getCrumbsLength",
    ]),
    areaTitle () {
       return this.$store.getters.getCategoryTitle
      },
    tests () {
      return this.$store.getters.getTests
    },
  },
  methods: {
    ...mapActions([
      "getCategorysDB",
      "setCrumbs"]),
    testLink(test){
      this.$router.push({name: 'test', params: { id: test.id } })
        this.setCrumbs({crumbs:[{
          name:'test',
          params: {id: test.id}, 
          title: `/${crumbsTitle(test)}`,
          iter: this.getCrumbsLength + 1 
          }]
        })
    }
    // :to="{ name: 'test', params: { id: test.id } }"
  },
  async created(){
    if (!this.tests) {this.isLoader = true}
    await this.getCategorysDB({page:null, parentId: this.$route.params.id})
    this.isLoader = false
  }
} 

</script>
<style lang="scss">
  
  .tests{
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
    }
    &__block{
      width: 100%;
      padding-top: 24px;
      background: var(--color-fon);
    }
  
  }
  .test__block{
    margin-bottom: 15px;
    & a{
      color: var(--color-blue);
      text-decoration: none;
    }
    &-info{
      display: flex;
      align-items: center;
      font-weight: 700;
      font-size: 16px;
      line-height: 24px;
      color: var(--color-blue);
      margin-top: 63px;
      padding-bottom: 63px;

      &:hover{
        cursor: pointer;
      }
    }
  }
  .test__card, .test__card-limitation{
    background-color: #FFFFFF;
    padding: 0 10px;
    display: flex;
    align-items: center;
    min-width: 0;
    word-wrap: break-word;
    border: 1px solid #269EB7;
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: var(--color-blue);
    min-height: 52px;
    
  }
  .test__card-limitation{
    border: 1px solid #E0E6EE;
    color: #E0E6EE;
    
  }
  .test__card:hover{
    background-color: #E4E9F0;
    cursor: pointer;
  }
  .test__card-development{
    background-color: #FFFFFF;
    padding: 0 10px;
    min-width: 0;
    border: 1px solid var(--color-red);
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: var(--color-blue);
    min-height: 52px;
  }

</style>
