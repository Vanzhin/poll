<template>
  <div class="bagraund">
    <Head>
      <Title>Авторизация | Амулет Тест</Title>
      <Meta name="description" :content="title" />
           
      <Meta name="robots" content="noindex"/>
      
    </Head>
   <section class="wrapper">
    <div 
      class="login-page"
    >
    <div class="login-page-text">Поздравляем!</div>
      <p>Вы успешно авторизовались. 
        И скоро будете перенаправлены на сайт. 
        Если этого не произойдет, кликните по ссылке. </p>
        <NuxtLink :to='user.getPageName'> Обратно на сайт</NuxtLink>
    </div>
  </section>
</div>
</template>
 
<script setup>
  definePageMeta({
    layout: "logout",
  });
  import { useUserStore  } from '../../stores/UserStore'
  const user = useUserStore()
  const route = useRoute()
  const rtoken = ref(route.params.rtoken ? route.params.rtoken: false)
 
  onMounted(async() => {
      if ( rtoken ) {
        await user.getAuthRefresh(rtoken)
        await user.savePageIsLocalStorage()
      }
      setTimeout(() => {
        navigateTo( user.getPageName )
      },15000);
    })
  
</script>

<style lang="scss" scoped>
.bagraund{
  position: fixed;
  top:0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #585858;
  z-index: 30;
}
.login-page {
  max-width: 380px;
  padding: 24px;
  background: #cad5d5;
  box-shadow: 0px 4px 15px rgba(34, 42, 70, 0.08);
  border-radius: var(--radius);
  margin: 100px auto;
  &-text{
    font-weight: 600;
    font-size: 18px;
    line-height: 26px;
    text-align: center;
  }
}
@media (min-width: 1024px) {
  
}
</style>