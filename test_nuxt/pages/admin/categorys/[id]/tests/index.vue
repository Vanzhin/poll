<template>
  <div>
    <AdminTheTestsPage />
  </div>
</template>
<script setup>
  definePageMeta({
    layout: "admin",
  })
  import { usePaginationStore } from '../../../../../stores/PaginationStore'
  const pagination = usePaginationStore()
  const route = useRoute()
  pagination.urlToChange(route.path)
</script>
