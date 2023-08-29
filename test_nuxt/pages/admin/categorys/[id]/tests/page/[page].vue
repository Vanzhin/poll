<template>
  <div>
    <AdminTheTestsPage />
  </div>
</template>
<script setup>
  definePageMeta({
    layout: "admin",
    middleware: 'authadmin'
  })
</script>