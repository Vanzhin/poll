<template>
  <div >
    <TheHomePage />
  </div>
</template>
<script setup>
  const route = useRoute()
  const page = ref(route.params.page ? +route.params.page: 1)
  onMounted(() => {
    console.log('я на клиенте')
    // console.dir( window)
    // window.scrollTo(pageXOffset,0)
    // window.scrollY = 0
    // window.addEventListener('scroll', function() {
    //   console.log(window.scrollY )
    //   // // window.scrollY = 0
      setTimeout(()=>{
        window.scrollTo(pageXOffset, 0)
      },2000)
      
    // });
  })


  useSeoMeta({
    title: `Амулет Тест | `,
    ogTitle: 'Амулет Тест | ',
    description: 'Сервис онлайн тестирования по вопросам охраны труда, промышленной безопасности (тесты Ростехнадзора), электробезопасности, тепловые установки. Онлайн подготовка и проверка знаний.',
    ogDescription: 'This is my amazing site, let me tell you all about it.',
    ogImage: 'https://example.com/image.png',
    twitterCard: 'summary_large_image',
  })
</script>
