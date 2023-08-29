<template>
  <div >
    <AdminTheChapterPage />
  </div>
</template>
<script setup>
    definePageMeta({
      layout: "admin",
      middleware: 'authadmin'
    })
</script>