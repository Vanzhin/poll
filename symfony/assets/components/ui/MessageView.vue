<template>
   <div class="cont-message"
      v-if="getMessage"
      @click.stop="visibleFalse"
    >
    <div class="cont-message-cont">
      <div class="cont-message-view">
        <p 
          :class="classObject"
          v-html="getMessage.mes"
        >
        </p>
      </div> 
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions} from "vuex"
export default {
  props: ['message'],
  data() {
    return {
    }
  },
  computed:{ 
    classObject() {
      const errClass = {
        error:  !this.getMessage ? false: this.getMessage.err,
        // message: !this.getMessage.err,
      }
      return errClass
    },
    ...mapGetters(["getMessage"]),
  },
  methods: {
    ...mapActions(["setMessageVisibleFalse"]),

    visibleFalse(){
      this.setMessageVisibleFalse()
    }
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
  
  &-cont{
    position: absolute;
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(240, 248, 255, 0.267);
  }
  
  &-view{
    
    min-height: 50px;
    min-width: 300px;
    // bottom: -40vh;
    max-width: 300px;
    max-height: 300px;
    z-index: 10;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #9ac7c7;
    border-radius: 10px;
    animation: move 0.3s linear;
    text-align: center;
    
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
    100% {
        transform: scaleY(1);
        opacity:  1;
    }
  }
</style>
