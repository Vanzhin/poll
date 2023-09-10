<template>
  <div >
    <AdminTheTestPage/>
  </div>
</template>
<script setup>
  definePageMeta({
    layout: "admin",
    middleware: 'authadmin'
  })
  
 </script>
