<template>
   <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <h2>Области аттестации</h2>
    </div>
   <div class="container">
      <div class="row">
        <div class="tests__block">
          <ItemEdit
            v-for="(item, index) in arrayForDraw" 
            :key="item.id"
            :item="item"
            :index="index"
          />
        </div>
      </div>
    </div>
  </div>
</template>
 
<script>
  import MessageView from "../../components/ui/MessageView.vue"
  import Loader from '../../components/ui/Loader.vue'
  import ItemEdit from "../../components/Admin/ItemEdit.vue"
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      MessageView,
      ItemEdit
    },
    data() {
      return {
        isLoader: true,
      }
    },
    computed:{ 
      ...mapGetters(["getAutchUserToken", "getMessage", "getTests"]),
      arrayForDraw () {
        return this.$store.getters.getTests
      },
    },
    methods: { 
      ...mapActions(["saveQuestionDb", "setMessage"]),
      
    },
    async created(){
      // await this.getCategorysDB({})
      // this.isLoader = false
      setTimeout(()=>this.isLoader = false, 1000 )
    }
   
 } 
 
</script>
<style lang="scss">
  .title{
    h2 {
      font-family: "Lato";
      font-style: normal;
      font-weight: 700;
      font-size: 30px;
      line-height: 40px;
      color: var(--color-blue);
    }
  }
</style>