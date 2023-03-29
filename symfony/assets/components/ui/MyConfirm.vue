<template>
   <div class="cont-message"
   v-if="getGonfimMessage !=='' "
   >
    <div class="cont-message-cont">
      <div class="confirm__window" >
        <div  class="confirm__children"
          v-html="getGonfimMessage"
        >
        </div>
        
        <div class="confirm__cont-button">
            <div class="confirm__button"
              @click.stop="configClick('yes')"
            >Да</div> 
            <div class="confirm__button"
              @click.stop="configClick('no')"
            >Нет</div> 
        </div>
      </div>
    </div>
  </div>
</template>
<script>

import { mapGetters, mapActions} from "vuex"
export default {
  data() {
    return {
    }
  },
  computed:{ 
    ...mapGetters(["getGonfimMessage","getGonfimVisible" ]),
  },
  methods: {
    ...mapActions([
      "setConfirmAction",
    ]),
    configClick(action){
      this.setConfirmAction(action)
    },
  },
  mounted(){
    console.log('mount confirm getGonfimMessage -', this.getGonfimMessage)
  },
  unmounted(){
  }
}

</script>

<style lang="scss" scoped>
.cont-message{
  position: relative;
  z-index: 21;

  &-cont{
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    
    background-color: rgba(240, 248, 255, 0.267);
  }
}
.confirm{
  &__window{
    width: 50%;
    min-height: 110px;
    background-color: rgb(253 253 253);
    border: 3px solid rgb(86 153 157);
    border-radius: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    animation: vizible 0.1s linear;
  }
   &__cont-button{
      margin: 10px;
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
