<template>
  <div >
   {{ parentId }} - {{ticketRnd}}
  </div>
</template>

<script setup>
import { useTestsStore } from '../../../stores/TestsStore'
  const route = useRoute()
  const ticketRnd = ref(route.params.rnd)
  const parentId = ref(+route.params.id)
  console.log(route)
  const tests = useTestsStore()
  
  if (!tests.getTestActive) {

  }




</script>