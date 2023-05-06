<template>
   <div class="cont-message"
    v-if="getFormInfoVisible"
   >
    <div class="cont-message-cont">
      <div class="confirm__window" >
        <div style="width: 100%;">
          <CloseView
            toLink="/statistics"
            @click="closeForm"
          />
        </div>
        <form @submit.prevent="onSubmit">
          <div class="login-page-text">Укажите недостающие данные для формирования отчета</div>
          <div class="form">
              <div class="text-field">
                <label class="text-field__label" >Имя</label>
                <input class="text-field__input"
                  type="text" 
                  placeholder="Введите имя"
                  v-model="lastName"
                  required
                />
              </div>
              <div class="text-field">
                <label class="text-field__label" >Фамилия</label>
                <input class="text-field__input"
                  type="text" 
                  placeholder="Введите фамилию"
                  v-model="firstName"
                  required
                />
              </div>
              <div class="text-field">
                <label class="text-field__label" >Отчество</label>
                <input class="text-field__input"
                  type="text" 
                  placeholder="Введите отчество"
                  v-model="middleName"
                  required
                />
              </div>
              <div
                v-if="getFormInfoParam === 'xml'"
              >
                <div class="text-field">
                  <label class="text-field__label" >СНИЛС</label>
                  <input class="text-field__input"
                    type="text" 
                    placeholder="123-235-122 12"
                    v-model="snils"
                    required
                    pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}( |-)[0-9]{2}"
                  />
                </div>
                <div class="text-field">
                  <label class="text-field__label" >Профессия (должность)</label>
                  <input class="text-field__input"
                    type="text" 
                    placeholder="Введите должность"
                    v-model="position"
                    required
                  />
                </div>
                <div class="text-field">
                  <label class="text-field__label" >ИНН организации работодателя:</label>
                  <input class="text-field__input"
                    type="text" 
                    placeholder="1234567891"
                    v-model="employerInn"
                    required
                    pattern="[0-9]{10}"
                  />
                </div>
                <div class="text-field">
                  <label class="text-field__label" >Место работы:</label>
                  <input class="text-field__input"
                    type="text" 
                    placeholder="Укажите место работы"
                    v-model="employerTitle"
                    required
                  />
                </div>
              </div>
              <input class="btn" type="submit" :value="buttonValue()"/>
                
          </div>
      </form>
        
        
      </div>
    </div>
  </div>
</template>
<script>
import CloseView from "./Close.vue"
import { mapGetters, mapActions } from "vuex"
export default {
  components: {
    CloseView,
  },
  data() {
    return {
      lastName:'',
      firstName:'',
      middleName:'',
      snils:'',
      position:'',
      employerInn:'',
      employerTitle:'',
      message: null,
    }
  },
  computed:{ 
    ...mapGetters([
      "getGonfimMessage",
      "getMessage",
      "getFormInfoVisible",
      "getFormInfoParam",
      "getAutchUserProfileFIO"
    ]),
    

  },
  methods: {
    ...mapActions([
      "changeFormInfoVisible",
      "getResultsXmlDb",
      "setAutchUserProfileFIO"
    ]),
    buttonValue() { 
      return this.getFormInfoParam === 'xml' ? 'Получить отчет XML' :
        this.getFormInfoParam === 'print'? 'Отчет на печать' : ''
    },
    onSubmit(e){
  
      switch (this.getFormInfoParam) {
        case 'xml':
          this.getReportXml()
          break;
        case "print":
          this.getReportPrint()
          break;
        default:
          alert( "Нет таких значений" );
      }
   
    },
    async getReportXml(){
      
        const info = {
          format: 'xml',
          worker: {
                    "lastName": this.lastName,
                    "firstName": this.firstName,
                    "middleName": this.middleName,
                    "snils": this.snils,
                    "position": this.position,
                    "employerInn":this.employerInn,
                    "employerTitle": this.employerTitle,
                },
        organization: {
                    "inn": this.employerInn,
                    "title": this.employerTitle,
                    },
        // test: {
        //             "learnProgramId": "2"
        //         } 
        }
      
        this.setAutchUserProfileFIO(info.worker)
        await this.getResultsXmlDb({info})
        this.message = !this.getMessage.err
        let timerId = setInterval(() => {
          if ( !this.getMessage) {
            clearInterval(timerId)
            if (this.message ){this.changeFormInfoVisible({visible: false, param: false})}
          }
        }, 200);

    },
    getReportPrint(){
      //передать ФИО
      const user = {
                    "lastName": this.lastName,
                    "firstName": this.firstName,
                    "middleName": this.middleName,
                  }
      this.setAutchUserProfileFIO(user)
      this.changeFormInfoVisible({visible: false, param: true})
    },
    closeForm(){
      this.changeFormInfoVisible({visible: false, param: false})
    }
    
  },
  mounted(){
  },
  unmounted(){
  },
  async created(){
    if (this.getAutchUserProfileFIO){
      this.lastName = this.getAutchUserProfileFIO.lastName
      this.firstName = this.getAutchUserProfileFIO.firstName 
      this.middleName = this.getAutchUserProfileFIO.middleName 
      this.snils = this.getAutchUserProfileFIO.snils
      this.position = this.getAutchUserProfileFIO.position
      this.employerInn = this.getAutchUserProfileFIO.employerInn
      this.employerTitle = this.getAutchUserProfileFIO.employerTitle
    }
  }
}

</script>

<style lang="scss" scoped>
.cont-message{
  position: fixed;
  z-index: 31;
  width: 100vw;
  height: 100vh;;
  &-cont{
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    
    background-color: rgb(148 171 191 / 83%);
  }
}
.confirm{
  &__window{
    width: 50%;
    min-height: 110px;
    background-color: rgb(253 253 253);
    border: 3px solid rgb(86 153 157);
    border-radius: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    animation: vizible 0.1s linear;
    padding: 30px;
    @media (max-width: 350px){
      width: 96%;
      padding: 10px 0;
      margin-top: 37px;
    }
  }
   &__cont-button{
      margin: 10px;
      display: flex;
   }
   &__children{
      margin: 10px;
      text-align: center;
   }
   &__button{
      margin: 0 15px;
      width: 60px;
      
      border: 1px solid #9bd3d3;
      border-radius: 5px;
      text-align: center;
      &:hover{
         cursor: pointer;
         background-color: rgb(162 228 233 / 52%);
      }
   }
   &__active{
      display: flex;
      align-items: end;
   }

}

@keyframes vizible {
   0% {
      transform: scale(0.1);
     }
   100% {
      transform: scale(1);
     }
 }
</style>
