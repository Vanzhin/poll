<template>
  <div
    class="item__block"
  >
    <div class="">
      <div class="item__card__parend"
        v-if="childVisible"
        @click.stop="childToggle"
      >
        Входит в:
      </div>
      <div class="item__card">
        <div 
          @click="childToggle"
        >
          {{ item.id }} - {{ item.title }}
        </div>
        <div class="btn-group" >
          <a href=""
            class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
          <a class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#deleteModal"
          
          ><i class="bi bi-trash3"></i></a>
        </div>
      </div>
      <div class="item__card__child"
        v-if="childVisible"
        @click.stop="childToggle"
      >
        Включает:
      </div>
    </div>
  </div>
</template>
<script>
import { RouterLink } from 'vue-router'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  props:['item', 'index'],
  data() {
    return {
      childVisible: false,
      count: 0
    }
  },
  computed:{
    ...mapGetters(["getIsAutchUser"]),
    areaTitle () {
        console.log(this.$route.params.id)
        console.log(this.$store.getters.getAreaTitle(this.$route.params.id))
        return this.$store.getters.getAreaTitle(this.$route.params.id)
      },
    
  },
  methods:{
    childToggle(){
      this.childVisible = !this.childVisible
    }
  }
} 

</script>
<style lang="scss" scoped>
  
  .item{
    &__block{
    margin: 10px 10px;}
  }
  .item__card{
    background-color: #e2e5fc;
    padding: 5px;
    display: flex;
    justify-content: space-between;
    min-width: 0;
    word-wrap: break-word;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem;
    &__child{
      height: 100px;
      background-color: rgb(219 216 227);
    }
    &__parend{
      height: 50px;
      background-color: rgb(213, 204, 238);
    }
  }
 .item__card:hover{
    background-color: aliceblue;
  }
    

</style>
