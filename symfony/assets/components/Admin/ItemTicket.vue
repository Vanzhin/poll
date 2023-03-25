<template>
  <div
    class="item__block"
  >
    <div class="">
      <div class="item__card">
        <div class="item__card-row" >
          <div class="item__card-header"> 
            <div class="item__card-block">
              <img :src="item.image" alt="" class="item__card__img"
                v-if="item.image"
              >
              <div class="item__card-title">
                {{ item.id }} - {{ item.title }}
              </div>
            </div>
            <div class="item__card-info">
              <div class="item__card-info-item">Вопросов: {{item.questionCount  }} </div>
              <div class="item__card-info-item">Билетов: {{item.ticketCount  }} </div>
              <div class="item__card-info-item">Секций: {{item.sectionCount  }} </div>
            </div>
          </div>
          <div class="btn-group" >
            <div class="btn btn-outline-primary btn-center"
              title="Редактировать"
              @click.stop="editTest"
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
              title="Добавить вопрос"
              @click.stop="addQuestion"
            >
              <i class="bi bi-question-square"></i>
            </div>
            <div class="btn btn-outline-primary btn-center"
              title="Импортировать вопросы из файла"
              @click.stop="importQuestionsFile"
            >
              <i class="bi bi-cloud-arrow-down"></i>
            </div>
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
    ...mapGetters([
      "getIsAutchUser", 
      "getCategoryParendId", 
      "getMessage", 
      "getTests",
      "getActivePage",
      "getGonfimAction",
      "getGonfimMessage"
  ]),
  },
  methods:{
    ...mapActions([
      "deleteTestDb", 
      "setTest",
      "setConfirmMessage",
      
    ]),
    img(item){
      const img = item ? item.slice(0, 4) + item.slice(5, item.length) : ''
      return img
    },
    childToggle(){
      this.childVisible = !this.childVisible
    },
    editTest(){
      console.log()
      this.$router.push({name: 'adminTestCreate', params: {operation:"edit" , id: this.item.id } })
    },
    async deleteTest(){
      console.log('Удаляю тест № - ', this.item.id)
      console.log('Удаляю тест № - ', this.$route)
      await this.deleteTestDb({
        id: this.item.id, 
        parentId: this.getCategoryParendId, 
        activePage: this.getActivePage,
        type: this.$route.meta.type,
      })
    },
    deleteVisibleConfirm(){
      this.setConfirmMessage("При удалении теста, так же будут удалены все его вопросы. Вы, действительно хотите это сделать?")
      let timerId = setInterval(() => {
        if (this.getGonfimAction) {
          clearInterval(timerId)
          if (this.getGonfimAction === "yes" ){this.deleteTest()}

        }{}
      }, 200);
    },
    importQuestionsFile(){
      this.setTest(this.item)
      this.$router.push({name: 'adminImportId',  params: {id: this.item.id}})
    },
    addQuestion(){
      this.setTest(this.item)
      this.$router.push({
        name: 'adminQuestionsCreate', 
        params: {
          testId: this.item.id,
          questionId:0,
          operation: "create"
        }
      })
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
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem;
    position: relative;
    &-header{
      flex: 10;
      
    }
    &-title{
      min-height: 40px;
    }
    &-info{
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      &-item{
        flex: 1;
        min-width: 150px;
      }
    }
    &-row{
      display: flex;
      justify-content: space-between;
      align-items: center;
      min-width: 0;
      word-wrap: break-word;
      flex-wrap: wrap;
    }
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
