<template>
  <div class="block">
    <div class="title">
      <h2>Форма редактирования компаний</h2>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <form @submit.prevent="onSubmit">
        <input type="hidden" 
          name="companyId" 
          :value="companyId"
          v-if="companyId"
        >
        <p class="label"><b>Название: </b></p>
        <div class="custom-radio img_block">
          <textarea rows="2" required
            name="title"
            v-model= "title"
            class="textarea_input" 
            
          ></textarea> 
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="title = ''"
            v-if="title !== ''"
          ></i>
        </div>
        <label class="label"><b>ИНН:</b> </label>
        <div class="custom-radio img_block">  
          <input  
            name="tin"
            v-model= "tin"
            class="input_alias"
            required 
            pattern="[0-9]{10}"
            placeholder="1234567891"
          />
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="tin = ''"
            v-if="tin !== ''"
          ></i>
        </div>
        <div
          v-if="operationCreate"
        >
          <label class="label"><b>E-mail:</b> </label>
          <div class="custom-radio img_block">  
            <input  
              name="email"
              v-model= "email"
              class="input_alias" 
            >
            <i class="bi bi-eraser custom-close" title="Очистить поле"
              @click="email = ''"
              v-if="email !== ''"
            ></i>
          </div>
          <label class="label"><b>Логин:</b> </label>
          <div class="custom-radio img_block">  
            <input  
              name="login"
              v-model= "login"
              class="input_alias" 
            >
            <i class="bi bi-eraser custom-close" title="Очистить поле"
              @click="login = ''"
              v-if="login !== ''"
            ></i>
          </div>
        </div>
        
      
        <br> 
          
        <button type="submit" class="button">Сохранить</button>
      </form>
    </div>
  </div>
</template>
 
<script setup>
  import { useCompanyStore } from '../../stores/CompanyStore'
  import { useModalStore } from '@/stores/ModalStore'
  const companys = useCompanyStore()
  const modal = useModalStore()
  console.log(companys)
  const route = useRoute()
    
  const title = ref("")
  const tin = ref("")
  const email = ref('')
  const login = ref('')
  const message = ref(null)
  const companyId = ref(null)
  const operationCreate = ref(true)
      
  async function onSubmit(e){
    const conpanySend = e.target
    
    if ( route.params.operation === 'edit'){
      await companys.editCompanyDB({conpanySend, id: companyId.value})
    } else if ( route.params.operation === 'create'){
      await companys.createCompanyDB({conpanySend})
    }
    message.value = !modal.getMessage.err
    let timerId = setInterval(() => {
      if ( !modal.getMessage ) {
        clearInterval(timerId)
        if (message.value ){
          if (route.params.companyId){
            navigateTo(`/admin/company/${route.params.companyId}`)
          } else {
            navigateTo(`/admin/companys`)
          }
        }
      }
    }, 200);
    
    
  }
      
      
   
      onMounted(async() => {
      if ( route.params.operation === 'create'){
        if (route.params.id > 0) {
          companyId.value = route.params.id 
        }
      }
      if ( route.params.operation === 'edit'){
        operationCreate.value = false
        companyId.value = route.params.companyId
        if (!companys.getCompany.id  ) {
          await companys.getCompanyIdDB({id: companyId.value})
        }
        title.value = companys.getCompany.title ? companys.getCompany.title : ''
        tin.value = companys.getCompany.tin ? companys.getCompany.tin : ''
        email.value = companys.getCompany.email ? companys.getCompany.email : ''
      }
      
      
    })
   
  
 
</script>
<style lang="scss" scoped>
  .button{
    padding: 5px 10px;
    transition: all 0.1s ease-out;
    &:hover{
      background-color: rgb(156, 156, 154);
    }
  }
  .label{
    display:block;
    margin: 0 10px;
  }
  .textarea_input{
    max-width: 50%;
    padding: 0;
    padding-left: 10px;
    margin: 5px;
  }
  .input_alias{
    width: 22%;
    border-color: rgb(243 243 238);
    margin: 5px;
  }
  .img_block{
    display: flex;
    align-items: flex-start;
    & i {
      margin: 10px;
      transition: all 0.5s ease-out;
      &:hover{
        color: rgb(185, 48, 14);
        transform: scale(1.25);
        cursor: pointer;
      }
    }
  }
 @media (min-width: 1024px) {
  
 }
</style>