<template>
   <Loader
    v-if="isLoader"
  />
  <div class="" v-else>
    <div class="tests__block conteiner">
      <h4>Сервис онлайн тестирования по промышленной безопасности приветствует вас!</h4>
    </div>
    <div class="tests__block conteiner">
      <div class="sections__block row" >
        <div class="card shadow col-sm-12 col-md-6 col-lg-4 col-xs-2 item"
          v-for="section in sections" 
          :key="section.id"
        >
          <div
            class="link"
            @click="categoryUpdate({id: section.id, title: section.title})"
          ><!-- @click="categoryUpdate(section.id)" -->
            <div class="card-header flex-shrink-1">
                <h5 class="my-0 font-weight-normal">{{ section.title }}</h5>
            </div>
            <div  class="img">
              <img :src="section.image" alt="" class="img"
                v-if="section.image"
              >
            </div>
           
            <div class="card-body">
              <h6>{{ section.information }}</h6>
            </div> 
          </div>
        </div>
      </div>
      <Pagination/>
    </div> 
  </div>
</template>
 
<script>
import { RouterLink } from 'vue-router'
import { mapGetters, mapActions, mapMutations} from "vuex"
import Loader from '../components/ui/Loader.vue'
import Pagination from '../components/Pagination.vue'
export default {
  components: {
    Loader,
    Pagination
  },
  data() {
    return {
      isLoader: true,
      count: 0,
      //  testName:''
      //  url:'http://test2-open/assets/img/',
      urlCss: 'url(http://localhost:8080/build/img/',
      urlCss: 'url(../img/'
    }
  },
  computed:{
    sections () {
      return this.$store.getters.getCategorys
    },
    
  },
  methods: {
    ...mapActions(["getCategorysDB", "setCategoryTitle"]),
    async categoryUpdate({id, title}){
      // this.setCategoryTitle(title)
      await this.getCategorysDB({page:null, parentId: id})
      this.$router.push({name: 'iter', params: { num: 1, id:id } })
      // this.$router.push({name: 'chapter', query: { iter: 1, group:id } })
    },
    img(item){
      console.log(item)

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
  .sections__block{
    display: flex;
    flex-wrap: wrap;
  }
  .item{
    margin-bottom: 5px;
    padding: 5px;
    height: 250px;
    // &:nth-child(1) .img{
    // background-image: url(../img/_prom.jpg);
    // }
    // &:nth-child(2) .img{
    //   background-image: url(../img/_electro5.jpg);
    // }
    // &:nth-child(3) .img{
    //   background-image: url(../img/_pb.jpg);
    // }
    // &:nth-child(4) .img{
    //   background-image: url(../img/_energo1.jpg);
    // }
    // &:nth-child(5) .img{
    //   background-image: url(../img/_naks.jpg);
    // }
    // &:nth-child(6) .img{
    //   background-image: url(../img/_ot.jpg);
    // }
  }
  .font-weight-normal {
    font-weight: 300;
    text-align: center;
  }
.img{
  
  width: 100%;
  height: 150px;
  
 
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