<template>
  <div class="cont-message"
   v-if="resultStore.getFormInfoVisible"
  >
    <uiLoaderViewMax/>
   <div class="cont-message-cont">
     <div class="confirm__window" >
       <div style="width: 100%;">
         <uiClose
           toLink="/user/statistics"
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
               v-if="resultStore.getFormInfoParam === 'xml'"
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
<script setup>
  import { useUserStore } from '../../stores/UserStore'
  import { useResultStore } from '../../stores/ResultStore'
  import { useModalStore } from '../../stores/ModalStore'
  const resultStore = useResultStore()
  const user = useUserStore()
  const modal = useModalStore()

  const lastName = ref('')
  const firstName = ref('')
  const middleName = ref('')
  const snils = ref('')
  const position = ref('')
  const employerInn = ref('')
  const employerTitle = ref('')
  const message = ref( null )
  function closeForm(){
    resultStore.saveFormInfo({visible: false, param: false})
  }
  function buttonValue() { 
      return resultStore.getFormInfoParam === 'xml' ? 'Получить отчет XML' :
      resultStore.getFormInfoParam === 'print'? 'Отчет на печать' : ''
  }
  function onSubmit(e){
    switch (resultStore.getFormInfoParam) {
      case 'xml':
        getReportXml()
        break;
      case "print":
        getReportPrint()
        break;
      default:
        alert( "Нет таких значений" );
    }
  }
  async function getReportXml(){
    const info = {
      format: 'xml',
      worker: {
        "lastName": lastName.value,
        "firstName": firstName.value,
        "middleName": middleName.value,
        "snils": snils.value,
        "position": position.value,
        "employerInn": employerInn.value,
        "employerTitle": employerTitle.value,
      },
      organization: {
        "inn": employerInn.value,
        "title": employerTitle.value,
      },
    }
    
    user.saveAutchUserProfileFIO(info.worker)
    await resultStore.getResultsXmlDb({info})
      message.value = !modal.getMessage.err
      let timerId = setInterval(() => {
        if ( !modal.getMessage) {
          clearInterval(timerId)
          if (message.value ){resultStore.saveFormInfo({visible: false, param: false})}
        }
      }, 200);

  }
  async function getReportPrint(){
    //передать ФИО
    const userinfo = {
      "lastName": lastName.value,
      "firstName": firstName.value,
      "middleName": middleName.value,
    }
    await user.saveAutchUserProfileFIO(userinfo)
    resultStore.saveFormInfo({visible: false, param: true})
  }

  onMounted(async() => {
    if (user.getAutchUserProfileFIO){
      lastName.value = user.getAutchUserProfileFIO.lastName
      firstName.value = user.getAutchUserProfileFIO.firstName 
      middleName.value = user.getAutchUserProfileFIO.middleName 
      snils.value = user.getAutchUserProfileFIO.snils
      position.value = user.getAutchUserProfileFIO.position
      employerInn.value = user.getAutchUserProfileFIO.employerInn
      employerTitle.value = user.getAutchUserProfileFIO.employerTitle
    }
     
  })
</script>
<style lang="scss" scoped>
.cont-message{
  position: fixed;
  z-index: 131;
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
    @media (max-width: 480px){
      width: 96%;
    }
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