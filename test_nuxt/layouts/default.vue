<template>
  <Head>
    <NoScript><div><img src="https://mc.yandex.ru/watch/93328607" style="position:absolute; left:-9999px;" alt="" /></div></NoScript>
  </Head>
  <div>
    <!-- <uiMessageView /> -->
    <UiFormInfoXML />
    <TheHeader />
    <slot />
    <TheFooter />
  </div>
</template>
<script setup>
  import { useUserStore  } from '../stores/UserStore'
  const route = useRoute()
  const page = ref(route.params.page ? +route.params.page: 1)
  onMounted(() => {
    const user = useUserStore()
    user.setTokenIsLocalStorage()
    setTimeout(()=>{
      window.scrollTo(pageXOffset, 0)
    },2000)
  })
</script>