<template>
  <section class="wrapper">
    <Head>
      <Title>Авторизация | Амулет Тест</Title>
      <Meta name="description" :content="user.getGlobalDescription" />
           
      <Meta name="robots" content="noindex"/>
      <link rel="canonical" href=""/>
    </Head>
    <div 
      class="login-page"
      v-if="emailSend"
      
    >
      <h1>{{resMessage}} </h1>
      
    </div>
      
    <div 
      class="login-page"
      v-else
      >
        <uiCloseView/>
        <form @submit.prevent="onSubmit">
          <div class="login-page-title">Вход на сайт</div>
          <div class="login-page-text">
            Введите действующий email<br>
            Вам на почту придет ссылка для авторизации
          </div>
          
          <div class="form">
            <div class="text-field">
              <label class="text-field__label" >E-mail</label>
              <input class="text-field__input"
                type="email" 
                placeholder="Введите email"
                name="email"
                v-model="email"
                required
              />
            </div>
            <div class="text-login">
                <NuxtLink to="/user/autch" class="routerLink"> 
                    <p> Войти с паролем</p>
                </NuxtLink>
              </div>
            <div class="text-login">
                <NuxtLink to="/user/signup" class="routerLink"> 
                    Пройти регистрацию
                </NuxtLink>
              </div>
            <input class="btn" type="submit" value="получить ссылку"/>
          </div>
          <div
            v-if="resMessage!==''"
          >
            {{ resMessage }}
          </div>
        </form>
    </div>
     
  </section>
</template>
 
<script setup>
  definePageMeta({
    layout: "logout",
  });
  import { useUserStore  } from '../../../stores/UserStore'
  const user = useUserStore()
     
  const count = ref(0)
  const email= ref('')
  const resMessage= ref('')
  const emailSend = ref(false)
  const link= ref('') 
  async  function onSubmit(e){
    await user.getLoginByLinkUser(email.value)
    emailSend.value = user.getLogoutLinkDate.send,
    resMessage.value = user.getLogoutLinkDate.message,
    link.value = user.getLogoutLinkDate.url
  } 
</script>

<style lang="scss" scoped>

@media (min-width: 1024px) {
  
}
</style>