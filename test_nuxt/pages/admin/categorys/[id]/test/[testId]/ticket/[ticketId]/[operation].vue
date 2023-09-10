<template>
  <div>
    <AdminEditTicket />
  </div>
</template>
<script setup>
  definePageMeta({
    layout: "admin",
    middleware: 'authadmin'
  })
</script>