<template>
  <div class="block">
   <AdminTheImportQuestionsFile />
  </div>
</template>
<script setup>
  definePageMeta({
    layout: "admin",
    middleware: 'authadmin'
  })
</script>