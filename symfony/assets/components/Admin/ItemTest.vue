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
              <div class="button-group" >
                <div class="button-item"
                  title="Редактировать"
                  @click.stop="editTest"
                >
                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M18.2407 6.75552L17.2857 5.71353C16.9165 5.31072 16.2924 5.27879 15.884 5.64183L14.3216 7.03088L16.6198 9.56793L18.1714 8.17538C18.5854 7.80377 18.6166 7.16567 18.2407 6.75552ZM5.5 14.8737L13.2006 8.02756L15.5035 10.5699L7.90674 17.388H5.5V14.8737ZM19.3465 5.74204L18.3915 4.70005C17.4686 3.69303 15.9083 3.61321 14.8874 4.5208L4.33557 13.9019C4.12212 14.0916 4 14.3636 4 14.6492V17.888C4 18.4403 4.44772 18.888 5 18.888H8.09822C8.34478 18.888 8.58266 18.7969 8.76616 18.6322L19.1733 9.29171C20.2084 8.36268 20.2863 6.76743 19.3465 5.74204ZM20 20.3847H4V21.8847H20V20.3847Z" fill="#269EB7"/>
              </svg>

                </div>
                <div class="button-item"
                  title="Добавить вопрос"
                  @click.stop="addQuestion"
                >
                  <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M19.001 10.4077C19.001 15.1021 15.1954 18.9077 10.501 18.9077C5.80656 18.9077 2.00098 15.1021 2.00098 10.4077C2.00098 5.71329 5.80656 1.90771 10.501 1.90771C15.1954 1.90771 19.001 5.71329 19.001 10.4077ZM20.501 10.4077C20.501 15.9306 16.0238 20.4077 10.501 20.4077C4.97813 20.4077 0.500977 15.9306 0.500977 10.4077C0.500977 4.88487 4.97813 0.407715 10.501 0.407715C16.0238 0.407715 20.501 4.88487 20.501 10.4077ZM11.2512 12.4957H9.48718V12.2717C9.48718 11.4177 9.76718 10.8857 10.6912 10.1437C11.6152 9.40171 11.9792 8.91171 11.9792 8.19771C11.9792 7.46971 11.3772 7.00771 10.5232 7.00771C9.66918 7.00771 8.94118 7.52571 8.94118 8.39371C8.94118 8.57571 8.98318 8.77171 9.03918 8.91171L7.42918 9.24771C7.30318 8.98171 7.23318 8.65971 7.23318 8.23971C7.23318 6.83971 8.47918 5.41171 10.4812 5.41171C12.5112 5.41171 13.7152 6.48971 13.7152 8.07171C13.7152 9.19171 13.3092 9.87772 11.9092 11.0817C11.4052 11.5157 11.2512 11.8797 11.2512 12.4957ZM11.2932 15.4077H9.44518V13.4757H11.2932V15.4077Z" fill="#269EB7"/>
                  </svg>
                </div>
                <div class="button-item"
                  title="Импортировать вопросы из файла"
                  @click.stop="importQuestionsFile"
                >
                  <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.42437 11.2804L6.598 10.4158C7.0572 8.12913 9.07919 6.40723 11.5 6.40723C13.5853 6.40723 15.3758 7.68415 16.1257 9.50449L16.4676 10.3345L17.3606 10.4255C19.1235 10.605 20.5 12.0963 20.5 13.9072C20.5 15.8402 18.933 17.4072 17 17.4072V18.9072C19.7614 18.9072 22 16.6687 22 13.9072C22 11.3189 20.0332 9.18986 17.5126 8.93318C16.5393 6.57045 14.2139 4.90723 11.5 4.90723C8.35069 4.90723 5.7245 7.14694 5.12736 10.1204C3.31336 10.7008 2 12.4006 2 14.4072C2 16.8925 4.01472 18.9072 6.5 18.9072H7V17.4072H6.5C4.84315 17.4072 3.5 16.0641 3.5 14.4072C3.5 13.0718 4.37365 11.9365 5.58444 11.5491L6.42437 11.2804ZM13.9455 17.1306L12.749 18.3368L12.749 12.9072H11.249L11.249 18.337L10.0524 17.1306L8.99603 18.1956L11.4707 20.6905C11.7624 20.9845 12.2354 20.9845 12.5271 20.6905L15.0018 18.1956L13.9455 17.1306Z" fill="#269EB7"/>
                  </svg>
                </div>
                <div class="button-item" 
                  title="Удалить"
                  @click.stop="deleteVisibleConfirm"
                >
                  <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.19118 5.73956C8.39757 4.39802 9.55188 3.40771 10.9092 3.40771H13.4775C14.8348 3.40771 15.9892 4.39802 16.1955 5.73956L16.2983 6.40767H17.6888H19.1934L19.1934 6.40771H20.1934V7.90771H19.0767L18.2419 18.6403C18.1205 20.2022 16.8176 21.4077 15.251 21.4077H9.13575C7.56912 21.4077 6.26627 20.2022 6.14479 18.6403L5.31003 7.90771H4.19336V6.40771H5.19336L5.19336 6.40767H6.69789H8.08839L8.19118 5.73956ZM14.7807 6.40767H9.60604L9.67373 5.96764C9.76755 5.35785 10.2922 4.90771 10.9092 4.90771H13.4775C14.0945 4.90771 14.6192 5.35785 14.713 5.96764L14.7807 6.40767ZM6.81456 7.90771L7.64027 18.524C7.70101 19.3049 8.35244 19.9077 9.13575 19.9077H15.251C16.0343 19.9077 16.6857 19.3049 16.7464 18.524L17.5722 7.90771L6.81456 7.90771Z" fill="#FF3F3F"/>
                  </svg>
                </div>
              </div>
            
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
      margin: 26px 0;
      position: relative;
    }
    
  }
  .item__card{
    height: 155px;
    padding: 20px 43px;
    background: #FFFFFF;
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    position: relative;
    font-family: 'Lato';
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 24px;
    color: #0B1F33;
    &:hover{
      outline: 1px solid var(--color-blue);
    }
    &-header{
      flex: 10;
      
    }
    &-title{
      min-height: 40px;
    }
    &-info{
      margin-top: 37px;
      display: flex;
      justify-content: space-between;
      align-items: center;
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
      border-bottom: 1px solid #E4E9F0;;
    }
  }
 .item__card:hover{
    
    cursor: pointer;
  }
  .btn-center{
    display: flex;
    align-items: center;
  }  

</style>
