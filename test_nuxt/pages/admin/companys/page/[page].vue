<template>
  <div >
    <AdminTheCompanysPage/>
  </div>
</template>
 
<script setup>
 definePageMeta({
    layout: "admin",
    middleware: 'authadmin'
  });
  
 
</script>