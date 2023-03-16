<template>
  <div
    class="item__block"
  >
    <div class="">
      <div class="item__card" @click="">
        <div class="item__card-block">
          <div class="item__card__img">
            <img :src="item.image" alt="" class="item__card__img"
              v-if="item.image"
            >
          </div>
          <div >
            {{ item.id }} - {{ item.title }}
          </div>
        </div>
        <div class="btn-group" >
          <div class="btn btn-outline-primary btn-center"
            title="Редактировать"
            @click.stop="editCategory"
          >
            <i class="bi bi-pencil"></i>
          </div>
          <div class="btn btn-outline-primary btn-center" 
          title="Удалить"
            @click.stop="deleteVisibleConfirm"
          >
            <i class="bi bi-trash3"></i>
          </div>
          <div class="btn btn-outline-primary btn-center"
            title="Добавить вложенную категорию"
            @click.stop="createChildrenCategory"
            v-if="item.test.length === 0"
          >
            <i class="bi bi-file-earmark"></i>
          </div>
          <div class="btn btn-outline-primary btn-center"
            title="Добавить тест"
            @click.stop="createChildrenTest"
            v-if="item.children.length === 0"
          >
            <i class="bi bi-archive"></i>
          </div>
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
  components: {
    
  },
  data() {
    return {
      id: null,
      childVisible: false,
      
    }
  },
  computed:{
    ...mapGetters(["getIsAutchUser", "getCategoryParendId","getGonfimAction",
      "getGonfimMessage"]),
  },
  methods:{
    ...mapActions(["deleteCategoryDb", "setConfirmMessage",]),
    img(item){
      const img = item ? item.slice(0, 4) + item.slice(5, item.length) : ''
      return img
    },
    childToggle(){
      this.childVisible = !this.childVisible
    },
    editCategory(){
      this.$router.push({name: 'adminsCategoryCreate', params: {operation:"edit" , id: this.item.id } })
    },
    async deleteCategoty(){
      console.log('Удаляю категорию № - ',this.item.id)
      this.deleteCategoryDb({id: this.item.id, parentId: this.getCategoryParendId, })
      
    },
    deleteVisibleConfirm(){

      this.setConfirmMessage("При удалении раздела, так же будут удалены все его внутренние области. Вы, действительно хотите это сделать?")
      let timerId = setInterval(() => {
        if (this.getGonfimAction) {
          clearInterval(timerId)
          if (this.getGonfimAction === "yes"){this.deleteCategoty()}
          
        }
      }, 200);
     },
    createChildrenCategory(){
      this.$router.push({name: 'adminsCategoryCreate', params: {operation:"create", id: this.item.id  } })
    },
    createChildrenTest(){
      this.$router.push({name: 'adminsTestCreate', params: {operation:"create", id: this.item.id  } })
    }
  }
} 

</script>
<style lang="scss" scoped>
  .item{
    &__block{
    margin: 10px 10px;
    position: relative;
  }
    
  }
  .item__card{
    background-color: #e2e5fc;
    padding: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-width: 0;
    word-wrap: break-word;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem;
    flex-wrap: wrap;
    position: relative;
    &__img{
      height: 100px;
      width: 150px;
      margin-right: 15px;
    }
    &__child{
      height: 100px;
      background-color: rgb(219 216 227);
    }
    &__parend{
      height: 50px;
      background-color: rgb(213, 204, 238);
    }
    &-block{
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }
  }
 .item__card:hover{
    background-color: aliceblue;
    cursor: pointer;
  }
  .btn-center{
    display: flex;
    align-items: center;
  }  

</style>
