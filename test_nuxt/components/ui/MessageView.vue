<template>
  <div>
    <div class="cont-message"
      v-if="modal.message"
      @click.stop="visibleFalse"
    >
      <div class="cont-message-cont">
        <div class="cont-message-view">
          <p 
            :class="classObject"
            v-html="modal.message.mes"
          >
          </p>
        </div> 
      </div>
    </div>
  </div>
</template>
<script setup>

  import { useModalStore  } from '../../stores/ModalStore'
  const modal = useModalStore()
  
  const classObject = computed(() => ({
    error:  !modal.message ? false: modal.message.err
  }))

  function visibleFalse(){
    modal.setMessageVisibleFalse()
  }
</script>

<style lang="scss" scoped>
.cont-message{
  position: relative;
  &-cont{
    position: fixed;
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(199, 223, 247, 0.678);
    z-index: 40;
  }
  &-view{
    min-height: 50px;
    min-width: 300px;
    // bottom: -40vh;
    max-width: 300px;
    max-height: 300px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: var(--color-blue);
    border-radius: 10px;
    animation: move 0.3s linear;
    text-align: center;
    color: var(--color-white);
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
