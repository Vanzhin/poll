<template>
  <div class="sections">
  <Loader
    v-if="isLoader"
  />
  <div class="" v-else>
    <div class="conteiner">
      <div class="sections__title">
        Сервис <span class="sections__title-select">онлайн-тестирования</span> по промышленной безопасности 
        приветствует вас!
      </div>
      <div class="sections__title-h2">
        Выберите область проверки знаний
      </div>
    </div>
    <div class="conteiner">
      <div class="sections__block row" >
        <div class="col-sm-12 col-md-6 col-lg-4 col-xs-2 item"
          v-for="section in sections" 
          :key="section.id"
          :style="`background-image: url(${section.image ? section.image 
            :'http://test2-open/img/item_fon.png'});`"
          @click="categoryUpdate({section})"
        >
          <div class="item-info">
            <div class="item-info-title">{{ section.title }}</div>
            <div class="item-info-discrabe">{{ section.description }}</div>
          </div>
        </div>
      </div>
      <Pagination
        type="getCategorysDB"
      />
    </div> 
  </div>
</div>
</template>
 
<script>
import { RouterLink } from 'vue-router'
import { mapGetters, mapActions, mapMutations} from "vuex"
import Loader from '../components/ui/LoaderView.vue'
import Pagination from '../components/Pagination.vue'
export default {
  components: {
    Loader,
    Pagination
  },
  data() {
    return {
      isLoader: true,
    }
  },
  computed:{
    ...mapGetters(["getTests",]),
    sections () {
      return this.$store.getters.getCategorys
    },
  },
  
  methods: {
    ...mapActions([
      "getCategorysDB",
      "setTests", 
      "setPagination", 
      "setCategoryParent",
      "setCategorys"
    ]),
    async categoryUpdate({section}){
      this.setCategoryParent(section)
      this.setPagination(null)
      this.getCategorysDB({page:null, parentId: section.id})
      if (section.test.length > 0) {
        this.setTests(section.test)
        this.$router.push({name: 'area', params: {id: section.id } })
        return
      } 
      this.setCategorys(section.children)
      this.$router.push({name: 'iter', params: { num: 1, id: section.id } })
    },
    img(item){
      const img = item ? item.slice(0, 4) + item.slice(5, item.length) : ''
      return img
    },
  },
  async created(){
    await this.getCategorysDB({})
    this.isLoader = false
  }
  
} 
 
</script>
<style lang="scss" scoped>
  .sections{
    
    min-height: 100vh;
    &__block{
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      max-width: 1110px;
      margin: auto;
    }
    &__title{
      margin-top: 77px;
      max-width: 630px;
      font-family: 'Lato';
      font-style: normal;
      font-weight: 700;
      font-size: 36px;
      line-height: 40px;
      color: var(--color-Black_blue);
      &-select{
        color: var(--color-blue);
        font-family: 'Lato';
        font-style: normal;
        font-weight: 700;
        font-size: 36px;
        line-height: 40px;
      }
      &-h2{
        margin-top: 50px;
        margin-bottom: 20px;
        font-family: 'Lato';
        font-style: normal;
        font-weight: 400;
        font-size: 24px;
        line-height: 40px;
        color: var(--color-Black_blue);
      }
    }
  }
  .item{
    margin: 15px 0;
    padding: 0;
    height: 277px;
    background: #848EA8;
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    max-width: 350px;
    display: flex;
    justify-content: center;
    align-items: flex-end;
    background-position: center;
    background-size: cover;
    
    &-info{
      text-align:left ;
      height: 137px;
      background: var(--color-fon);
      border-radius: 6px;
      width: 90%;
      padding: 16px 26px;
      &-title{
        height: 50%;
        font-family: 'Lato';
        font-style: normal;
        font-weight: 700;
        font-size: 24px;
        line-height: 29px;
        color: var(--color-Black_blue);
      }
      &-discrabe{
        font-family: 'Lato';
        font-style: normal;
        font-weight: 400;
        font-size: 14px;
        line-height: 20px;
        color: #66727F;
      }
    }
    &:hover{
      .item-info{
        background: var(--color-blue);
        &-title{
          color:var(--color-white);
        }
        &-discrabe{
          color:var(--color-white);
        }
      }
    }
  }
  .font-weight-normal {
    font-weight: 300;
    text-align: center;
  }

.link{
  text-decoration: none;
  color: #204242;
  &:hover{
    text-decoration:underline;
    color: cadetblue;
    cursor: pointer;
  }
}
 @media (min-width: 1024px) {
  
 }
</style>