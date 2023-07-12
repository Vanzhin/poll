<template>
  <div >
   {{ parentId }} - {{ticketRnd}}
  </div>
</template>

<script setup>
  const route = useRoute()
  const ticketRnd = ref(route.params.rnd)
  const parentId = ref(+route.params.id)
  console.log(route)
</script>