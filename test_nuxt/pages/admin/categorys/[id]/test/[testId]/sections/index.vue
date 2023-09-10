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
  import { usePaginationStore } from '@/stores/PaginationStore'
  const pagination = usePaginationStore()
  const route = useRoute()
  pagination.urlToChange(route.path)
 </script>


