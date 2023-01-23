<script setup>
  import { RouterLink } from 'vue-router'
</script>

<template>
  <main>
    домашняя страница
  </main>
    <RouterLink :to="{ name: 'tests' }">
      <p>Тест</p>
    </RouterLink>
    <RouterLink :to="{ name: 'questions' }">
      <p>Вопросы</p>
    </RouterLink>
</template>
