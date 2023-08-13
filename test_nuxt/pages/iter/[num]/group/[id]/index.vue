<template>
  <div >
    <TheIterPage />
  </div>
</template>

<script setup>
  import { usePaginationStore } from '../../../../../stores/PaginationStore'
  const route = useRoute()
  const pagination = usePaginationStore()
  pagination.urlToChange(route.path)
</script>