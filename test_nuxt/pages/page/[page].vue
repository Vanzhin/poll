<template>
  <div>
    <TheHomePage />
  </div>
</template>
<script setup>
const route = useRoute()
const page = ref(route.params.page ? +route.params.page: 1)



useSeoMeta({
  title: `Амулет Тест | ${page.value > 1 ?'page '+page.value: ''}`,
  ogTitle: 'Амулет Тест | ',
  description: 'Сервис онлайн тестирования по вопросам охраны труда, промышленной безопасности (тесты Ростехнадзора), электробезопасности, тепловые установки. Онлайн подготовка и проверка знаний.',
  ogDescription: 'This is my amazing site, let me tell you all about it.',
  ogImage: 'https://example.com/image.png',
  twitterCard: 'summary_large_image',
})
</script>
