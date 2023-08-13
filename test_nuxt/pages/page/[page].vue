<template>
  <Head>
      <Title>Амулет Тест | Тесты Ростехнадзора по промышленной безопасности и электробезопасности</Title>
      <Meta name="description" content="Амулет Тест - Тесты Ростехнадзора по промышленной безопасности и электробезопасности" />
    </Head>
  <div>
    <TheHomePage />
  </div>
</template>
<script setup>
  const route = useRoute()
  const page = ref(route.params.page ? +route.params.page: 1)

</script>
