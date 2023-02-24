<template>
   <div class="cont-message"
      v-if="message"
    >
    <div class="cont-message-view">
      <p 
        :class="classObject"
      >
        {{ message.mes }}
      </p>
    </div> 
  </div>
</template>
<script>

export default {
  props: ['message'],
  data() {
    return {
      classObject: {
        error: this.message.err,
        message: !this.message.err,
      }
    }
  },
  computed: {
    
  },
  methods: {
   
  },
  mounted(){
    
  },
  unmounted(){
   
  }
}

</script>

<style lang="scss" scoped>
.cont-message{
  position: relative;
  display: flex;
  justify-content: center;
  
  &-view{
    position: absolute;
    min-height: 50px;
    min-width: 200px;
    bottom: -26px;
    max-width: 200px;
    z-index: 10;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #9ac7c7;
    border-radius: 10px;
    animation: move 5s 1 linear;
    text-align: center;
    transform: scaleY(0);
    padding: 15px;
    p{
      margin: 0;
    }
  }
}
.error{
  color: rgb(217 50 80);
}
@keyframes move {
    0% {
        transform: scaleY(0.5);
        opacity:  0.5;
    }
    5% {
        transform: scaleY(1);
        opacity:  1;
      }
    80% {
        transform: scaleY(1);
        opacity:  1;
      
      }  
    100% {
        transform: scaleY(0);
        opacity:  0;
    }
  }
</style>
