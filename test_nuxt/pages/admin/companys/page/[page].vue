<template>
  <div >
    <AdminTheCompanyPage/>
  </div>
</template>
 
<script setup>
 definePageMeta({
    layout: "admin",
    middleware: 'authadmin'
  });
  
 
</script>