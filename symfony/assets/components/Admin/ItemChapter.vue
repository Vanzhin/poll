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
            @click.stop="editCategory(item.id)"
          >
            <i class="bi bi-pencil"></i>
          </div>
          <div class="btn btn-outline-primary btn-center" 
          title="Удалить"
            @click.stop="deleteVisibleConfirm(item.id)"
          >
            <i class="bi bi-trash3"></i>
          </div>
          <div class="btn btn-outline-primary btn-center"
            title="Добавить вложенную категорию"
            @click.stop=""
          >
            <i class="bi bi-file-earmark"></i>
          </div>
          <div class="btn btn-outline-primary btn-center"
            title="Добавить тест"
            @click.stop=""
          >
            <i class="bi bi-archive"></i>
          </div>
        </div>
        <MyConfirm
          :message="confirmMessage"
          :visible="confirmVisible"
          @yesConfirm="confirmYes"
          @noConfirm="confirmVisible = false"
        />
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
import MyConfirm from '../ui/MyConfirm.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  props:['item', 'index'],
  components: {
    MyConfirm
  },
  data() {
    return {
      childVisible: false,
      confirmMessage: '',
      confirmVisible: false,
      id: null,
      confirmYes:null
    }
  },
  computed:{
    ...mapGetters(["getIsAutchUser"]),
  },
  methods:{
    ...mapActions(["deleteCategoryDb"]),
    img(item){
      const img = item ? item.slice(0, 4) + item.slice(5, item.length) : ''
      return img
    },
    childToggle(){
      this.childVisible = !this.childVisible
    },
    editCategory(id){
      this.$router.push({name: 'adminsCategoryCreate', params: {operation:"edit" , id:id } })
    },
    async deleteCategoty(){
      console.log('Удаляю категорию № - ', this.id)
      this.deleteCategoryDb({id: this.id})
      this.confirmVisible = false
    },
    deleteVisibleConfirm(id){
      this.confirmMessage = "При удалении раздела, так же будут удалены все его внутренние области. Вы, действительно хотите это сделать?"
      this.confirmVisible = true
      this.id = id
      this.confirmYes = this.deleteCategoty
    },
   
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
