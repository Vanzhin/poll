<template>
   <div class="cont-message">
    <div class="confirm__window" >
      <div  class="confirm__children">
         {{message}}
      </div>
      
      <div class="confirm__cont-button">
          <div class="confirm__button"
          @click.stop="$emit('yesConfirm')"
          >Да</div> 
          <div class="confirm__button"
              @click.stop="$emit('noConfirm')"
          >Нет</div> 
      </div>
    </div>

  </div>
</template>
<script>
import { mapGetters } from "vuex"
export default {
  props: ['message',],
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
  },
  mounted(){
  },
  unmounted(){
  }
}

</script>

<style lang="scss" scoped>
.cont-message{
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  position: absolute;
  z-index: 21;
  background-color: rgba(114, 113, 112, 0.63);
}
.confirm{
  &__window{
    width: 50%;
    height: 100%;
    background-color: rgb(253 253 253);
    border: 3px solid #b37ed6;
    border-radius: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    animation: vizible 0.1s linear;
  }
   &__cont-button{
     
      display: flex;
   }
   &__children{
      margin: 10px;
      text-align: center;
   }
   &__button{
      margin: 0 15px;
      width: 60px;
      
      border: 1px solid #9bd3d3;
      border-radius: 5px;
      text-align: center;
      &:hover{
         cursor: pointer;
         background-color: rgb(162 228 233 / 52%);
      }
   }
   &__active{
      display: flex;
      align-items: end;
   }

}

@keyframes vizible {
   0% {
      transform: scale(0.1);
     }
   100% {
      transform: scale(1);
     }
 }
</style>
