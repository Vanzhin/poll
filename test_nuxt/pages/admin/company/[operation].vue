<template>
  <div >
    <AdminEditCompany />
  </div>
</template>
<script setup>
  definePageMeta({
    layout: "admin",
    middleware: 'authadmin'
  })

</script>