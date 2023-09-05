<template>
  <div>
    <AdminEditQuestion />
  </div>
</template>
<script setup>
  definePageMeta({
    layout: "admin",
    middleware: 'authadmin'
  })
</script>
