<template>
  <div>
    <AdminEditSection />
  </div>
</template>
<script setup>
  definePageMeta({
    layout: "admin",
    middleware: 'authadmin'
  })
</script>