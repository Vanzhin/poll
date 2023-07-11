<template>
  <div >
    Билет {{ ticketNum }}
  </div>
</template>
<script setup>
const route = useRoute()
  const ticketNum = ref(+route.params.num)
</script>