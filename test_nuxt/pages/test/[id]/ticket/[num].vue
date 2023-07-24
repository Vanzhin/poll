<template>
  <div >
    Билет {{ ticketNum }}
  </div>
</template>
<script setup>
  const route = useRoute()
  const ticketNum = ref(+route.params.num)
  const ticketRnd = ref(route.params.rnd)
  const parentId = ref(+route.params.id)
</script>